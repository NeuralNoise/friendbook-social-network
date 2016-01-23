<?php

/**
 * Created by PhpStorm in Toronto.
 * User: Joe
 * Date: 21-Sep-15
 * Time: 8:26 PM
 */

class User {

    public $user_row;

    /**
     * A user constructor.
     * @param $user_id int The user id of the user to get.
     */
    public function __construct( $user_id ) {

        $user_row = User::get_user_row_by_id( $user_id );

        if( empty( $user_row ) ) {
            $this->user_row = array();
        } else {

            // remove the password from the user row
            unset( $user_row[0]["Password"] );
            $this->user_row = $user_row[0];

        }

    }

    /**
     * toString method to be be able to print a user object.
     * @return string The full name of the user.
     */
    public function __toString() {
        return $this->get_first_name() . " " . $this->get_last_name();
    }

    /**
     * Gets the first name of the user.
     * @return string The first name of the user.
     */
    public function get_first_name() {
        return $this->user_row["Firstname"];
    }

    /**
     * Gets the last name of the user.
     * @return string The last name of the user.
     */
    public function get_last_name() {
        return $this->user_row["Lastname"];
    }

    /**
     * Gets the profile picture of the user.
     * @return string The profile picture of the user.
     */
    public function get_profile_picture() {
        return $this->user_row["ProfilePicture"];
    }

    /**
     * Get the date created of the user, in the format specified.
     * @param string $format The format of the date.
     * @return bool|string The date created.
     */
    public function get_formatted_date_created( $format="jS \of F, Y" ) {
        $date = $this->user_row["DateCreated"];
        return date($format, strtotime($date));
    }

    /**
     * Gets the email of the user.
     * @return string The email of the user.
     */
    public function get_email() {
        return $this->user_row["Email"];
    }

    /**
     * Gets the gender of the user.
     * @return string The gender of the user.
     */
    public function get_gender() {
        return $this->user_row["Gender"] === 'm' ? "male" : "female";
    }

    /**
     * Gets the date of birth of the user in the format specified.
     * @param string $format The format of the date.
     * @return string The date of birth of the user.
     */
    public function get_date_of_birth( $format="d/m/Y" ) {
        $date_of_birth = $this->user_row["DateOfBirth"];
        return date($format, strtotime($date_of_birth));
    }

    /**
     * Get the age of the user.
     * @return int The age of the user.
     */
    public function get_age() {
        $date_of_birth = explode("/", $this->get_date_of_birth());

        /**
         * Separate the date into its components.
         */
        $day   = $date_of_birth[0];
        $month = $date_of_birth[1];
        $year  = $date_of_birth[2];

        /**
         * Get the components of the current date.
         */
        $curr_day   = (int)date('d');
        $curr_month = (int)date('m');
        $curr_year  = (int)date('Y');

        $age = $curr_year - $year;

        if( $curr_month === $month && $curr_day < $day ) {
            $age -= 1;
        } elseif( $curr_month < $month ) {
            $age -= 1;
        }

        return $age;
    }

    /**
     * Checks whether an email exists in the database
     * @param $email string The email whose existence to check
     * @return bool Whether the email exists in the database or not
     */
    public static function email_exists( $email ) {

        // the user row with the particular email; it could be empty
        $user_row = User::get_user_row_by_email( $email );

        // return false if the user row is null
        return empty( $user_row ) ? false : true;

    }

    /**
     * @param $signup_object object The signup object used to create the user
     */
    public static function create_user( $signup_object ) {

        // hash the password before inserting it into the database
        $signup_object->hash_password();

        $email     = $signup_object->email;
        $firstname = $signup_object->firstname;
        $lastname  = $signup_object->lastname;
        $password  = $signup_object->password;
        $gender    = $signup_object->gender;
        $birthday  = date("Y-m-d", mktime(0, 0, 0, $signup_object->birth_month, $signup_object->birth_day, $signup_object->birth_year));
        $profile_pic = $signup_object->profile_picture;

        $sql_query  = 'INSERT INTO `' . Constant::user_database_name . '`';
        $sql_query .= '(`Email`, `Firstname`, `Lastname`, `Password`, `Gender`, `DateOfBirth`, `ProfilePicture`) VALUES ';
        $sql_query .= '("' . $email . '", "' . $firstname . '", ';
        $sql_query .= '"' . $lastname . '", "' . $password . '", ';
        $sql_query .= '"' . $gender . '", "' . $birthday . '", ';
        $sql_query .= '"' . $profile_pic . '");';

        $connection = create_connection();

        if( process_query( $sql_query, $connection ) ) {
            close_connection( $connection );
            return true;
        } else {
            close_connection( $connection );
            return false;
        }

    }

    /**
     * Get the row corresponding to the user from the database.
     * @param $email string The email of the user whose row to get.
     * @return array The array containing the row of the user, if it exists
     */
    public static function get_user_row_by_email( $email ) {

        $sql_query  = 'SELECT * FROM `' . Constant::user_database_name . '` ';
        $sql_query .= 'WHERE Email="' . $email . '";';

        $connection = create_connection();

        // the user row with the particular email; it could be empty
        $user_row = process_query( $sql_query, $connection, MYSQLI_ASSOC );

        // close the connection before exiting the function
        close_connection( $connection );

        // returns an empty array if the user row doesn't exists
        return empty($user_row) ? array() : $user_row;

    }

    /**
     * @param $user_id int The id of the user whose user row to retrieve from the database.
     * @return array The array containing the row of the user, if it exists.
     */
    public static function get_user_row_by_id( $user_id ) {

        $sql_query  = 'SELECT * FROM `' . Constant::user_database_name . '` ';
        $sql_query .= 'WHERE UserID="' . $user_id . '";';

        $connection = create_connection();

        // the user row with the particular id; it could be empty
        $user_row = process_query( $sql_query, $connection, MYSQLI_ASSOC );

        // close the connection before exiting the function
        close_connection( $connection );

        // returns an empty array if the user row doesn't exists
        return empty($user_row) ? array() : $user_row;

    }

    /**
     * Checks if two users are the same (same user ID)
     * @param $user User The user to compare
     * @param $other_user User The user to compare
     * @return bool Whether the users are the same.
     */
    public static function users_are_the_same( $user, $other_user ) {
        return $user->user_row["UserID"] === $other_user->user_row["UserID"];
    }

}

?>