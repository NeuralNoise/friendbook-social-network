<?php

/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 26-Oct-15
 * Time: 12:18 AM
 */

class Login {

    public $email_valid;
    public $email;

    public  $password_valid;
    private $password; // the password is never meant to be accessed

    /**
     * @var array The array containing the information about the user, if it exists, from the database
     */
    public $user_row;

    /**
     * @var bool Whether The login is successful or not.
     */
    public $login_successful;

    /**
     * @param $post_data array The post data from the login page.
     */
    public function __construct( $post_data ) {

        if( !$this->post_data_is_valid( $post_data ) ) {
            $this->login_successful = false;
        } else {

            // get the email & the password from the POST array
            $this->email    = $post_data["login-email"];
            $this->password = $post_data["login-password"];

            $this->login_successful = $this->login_successful();

        }

    }

    /**
     * @param $post_data array The post data from the login page.
     * @return bool Whether the post data is valid or not
     */
    private function post_data_is_valid( $post_data ) {
        if( empty($post_data) || !isset($post_data) ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks to see if the email's format is valid (no illegal characters)
     * @return bool Whether the email format is fine or not
     */
    private function email_format_valid() {
        // get the email so I don't have to type in a long variable name
        $email = $this->email;

        // the regex that the email MUST match
        $regex_to_match = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";

        // return false if the email does not conform to email rules
        return !( $email === "" || !preg_match( $regex_to_match, $email ) );
    }

    /**
     * Checks to see if the password's format is valid (a certain length)
     * @return bool Whether the password format is valid.
     */
    private function password_format_valid() {
        $password = $this->password;

        // the min and max length the password can be
        $min_length = Constant::password_min_length;
        $max_length = Constant::password_max_length;

        // the length of the password
        $password_length = strlen( $password );

        return $password_length <= $max_length && $password_length >= $min_length;
    }

    /**
     * Gets the user row from the database.
     */
    private function get_user_row() {
        // Return out of the function if the user row is set
        if( isset( $this->user_row ) ) {
            return;
        } else {

            // get the user row by the email
            $user_row = User::get_user_row_by_email( $this->email );

            $this->user_row = $user_row;

        }
    }

    /**
     * Checks whether the email/password combination is valid
     * @return bool Email / password combination is valid
     */
    private function login_successful() {
        // before accessing the database, check the formats to see if they're fine
        $email_format_valid = $this->email_format_valid();
        $this->email_valid = $email_format_valid;

        $password_format_valid = $this->password_format_valid();
        $this->password_valid = $password_format_valid;

        if( !$email_format_valid || !$password_format_valid ) {
            $this->login_successful = false;
            return false;
        }

        $this->get_user_row();

        // the row containing the user info
        $user_row = $this->user_row;

        if( empty( $user_row ) ) {
            return false;
        }

        if( $this->email === $user_row[0]["Email"] ) {
            $this->email_valid = true;
        } else {
            $this->email_valid = false;
            return false;
        }

        $password_given = $this->password;
        $password_user  = $user_row[0]["Password"];

        if( password_verify( $password_given, $password_user ) ) {
            // the passwords don't match
            $this->password_valid = true;
        } else {
            $this->password_valid = false;
            return false;
        }

        // everything's fine, so return true
        return true;
    }

    /**
     * Increments the number of times the user has logged in.
     */
    public function increment_login_counter() {
        $user_id = $this->user_row[0]["UserID"];

        $sql_query  = 'INSERT INTO `'. Constant::login_count_database_name . '`(`UserID`, `LoginTimes`) ';
        $sql_query .= 'VALUES (' . $user_id . ', 1) ON DUPLICATE KEY UPDATE ';
        $sql_query .= '`LoginTimes` = `LoginTimes` + 1;';

        $connection = create_connection();

        process_query($sql_query, $connection);

        close_connection($connection);

        return $sql_query;
    }

}

?>