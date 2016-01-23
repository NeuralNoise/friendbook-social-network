<?php

class Signup {

    // define the constructor variables
    public $firstname;
    public $lastname;

    public $password;
    public $password_repeat;

    public $email;

    public $gender;

    public $birth_month;
    public $birth_day;
    public $birth_year;

    public $profile_picture;

    // the variables denoting whether the first name, last name, etc. are valid
    public $valid_firstname;
    public $valid_lastname;
    public $valid_password;
    public $valid_email;
    public $valid_gender;
    public $valid_birthday;

    /**
     * @param $post_data array The post data from the form collected.
     */
    public function __construct( $post_data ) {

        // if the post data is not valid the valid signup flag is set to false
        if( !$this->post_data_is_valid( $post_data ) ) {
            $this->valid_signup = false;
        } else {

            // fill in the constructor variables
            $this->firstname       = $post_data["signup-firstname"];
            $this->lastname        = $post_data["signup-lastname"];
            $this->password        = $post_data["signup-password"];
            $this->password_repeat = $post_data["signup-password-repeat"];
            $this->email           = $post_data["signup-email"];

            // because none of the gender radio buttons could've been selected
            if( isset( $post_data["signup-gender"] ) ) {
                $this->gender = $post_data["signup-gender"];
            } else {
                $this->gender = "";
            }

            $this->birth_month = $post_data["signup-month"];
            $this->birth_day   = $post_data["signup-day"];
            $this->birth_year  = $post_data["signup-year"];

            $this->valid_firstname = $this->name_is_valid( $this->firstname );
            $this->valid_lastname  = $this->name_is_valid( $this->lastname );
            $this->valid_password  = $this->password_is_valid();
            $this->valid_email     = $this->email_is_valid();
            $this->valid_gender    = $this->gender_is_valid();
            $this->valid_birthday  = $this->birthday_is_valid();

            // the sign up is only valid if everything is valid
            $this->valid_signup =
                $this->valid_firstname &&
                $this->valid_lastname  &&
                $this->valid_password  &&
                $this->valid_email     &&
                $this->valid_gender    &&
                $this->valid_birthday;

            $this->set_default_profile_picture();

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
     * @param String $name The name whose validity to check
     * @return bool Whether the name is valid or not. It is only valid if it has
     * no invalid characters and if it is a certain length.
     */
    private function name_is_valid( $name ) {

        // get the min and max length a name can have from the constants file
        $name_min_length = Constant::name_min_length;
        $name_max_length = Constant::name_max_length;

        if( $name === "" ) {
            // if the name is empty then just return false
            return false;
        } elseif( strlen($name) < $name_min_length || strlen($name) > $name_max_length ) {
            // if the name is longer than required, return false
            return false;
        } elseif( preg_match( "/[^a-z ]/i", $name ) ) {
            // if an illegal character is found in the name, return false
            return false;
        } else {
            // if all tests pass, return true
            return true;
        }
    }

    /**
     * @return bool Whether the two passwords match and are valid
     */
    private function password_is_valid() {
        // the min and max length a password can be
        $password_min_length = Constant::password_min_length;
        $password_max_length = Constant::password_max_length;

        // just so I don't have to type in a long ass variable name
        $password = $this->password;
        $password_repeat = $this->password_repeat;

        if( $password === "" || $password_repeat === "" ) {
            // if either password field was left empty, return false
            return false;
        } elseif( $password !== $password_repeat ) {
            // if the passwords don't match, return false
            return false;
        } elseif( strlen($password) < $password_min_length || strlen($password) > $password_max_length ) {
            // if the password is longer than the specified range, return false
            // I only tested $password because they matched in the previous condition
            return false;
        } else {
            // all tests passed, so return true
            return true;
        }
    }

    /**
     * Modifies the password member variable to a hashed version of itself.
     */
    public function hash_password() {

        $password = $this->password;

        // the options to hash the password with.
        $options = [ 'cost' => 13 ];
        $hashed_password = password_hash( $password, PASSWORD_BCRYPT, $options );

        $this->password = $hashed_password;

    }

    /**
     * @return bool Whether the email is valid or not.
     */
    private function email_is_valid() {
        // get the email so I don't have to type a long variable name each time
        $email = $this->email;

        $regex_to_match = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";

        if( $email === "" ) {
            return false;
        } elseif( !preg_match( $regex_to_match, $email ) ) {
            // if the regex doesn't match, return false
            return false;
        } else {

            // check if the email exists
            $email_exists = User::email_exists( $email );

            return !$email_exists;

        }
    }

    /**
     * Checks if the gender entered is valid (it should be male or other)
     * @return bool Whether the gender entered is valid or not
     */
    private function gender_is_valid() {
        // the gender entered
        $gender = $this->gender;

        // if the gender is not male or female false is returned
        if( $gender !== "male" && $gender !== "female" ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the user is older than the required age
     * @param $month int The birth month of the user
     * @param $day int The birth day of the user
     * @param $year int The birth year of the user
     * @return bool Whether the user is older than required or not
     */
    private function older_than_required_age( $month, $day, $year ) {

        // get the current day, month, and year
        $curr_month = date("n");
        $curr_day   = date("j");
        $curr_year  = date("Y");

        $age = $curr_year - $year;

        if( $curr_month === $month && $curr_day < $day ) {
            $age -= 1;
        } elseif( $curr_month < $month ) {
            $age -= 1;
        }

        // the required age to sign up for friendbook
        $required_age = Constant::required_age_to_signup;

        return $age >= $required_age;

    }

    /**
     * Checks if the birthday combination is valid (not something like February 31 or the like)
     * @param $month int The birth month of the user
     * @param $day int The birth day of the user
     * @param $year int The birth year of the user
     * @return bool Whether the birthday comb is valid or not
     */
    private function birthday_combination_is_valid( $month, $day, $year ) {

        switch( $month ) {

            case 1:
                // January
                return $day <= 31;

            case 2:
                // February
                if( $year % 4 === 0 && $year % 100 === 100  && $year % 400 === 0 ) {
                    // it's a leap year, and there are 29 days in Feb on leap years
                    return $day <= 29;
                } else {
                    // it's not a leap year, so there are only 28 days
                    return $day <= 28;
                }

            case 3:
                // March
                return $day <= 31;

            case 4:
                // April
                return $day <= 30;

            case 5:
                // May
                return $day <= 31;

            case 6:
                // June
                return $day <= 30;

            case 7:
                // July
                return $day <= 31;

            case 8:
                // August
                return $day <= 31;

            case 9:
                // September
                return $day <= 30;

            case 10:
                // October
                return $day <= 31;

            case 11:
                // November
                return $day <= 30;

            case 12:
                // December
                return $day <= 31;

            default:
                // just to be safe
                return false;

        }

    }

    /**
     * Check if the birthday entered is valid
     * @return bool Whether the birthday is valid or not
     */
    private function birthday_is_valid() {

        // get the birth month, birth day, and birth year
        $birth_month = $this->birth_month;
        $birth_day   = $this->birth_day;
        $birth_year  = $this->birth_year;

        if( $birth_month === 0 || $birth_day === 0 || $birth_year === 0 ) {
            // the birth month, birth day, and birth year can't be 0
            return false;
        } elseif( !$this->birthday_combination_is_valid( $birth_month, $birth_day, $birth_year ) ) {
            // this means the birthday combination is not valid, obviously
            return false;
        } elseif( !$this->older_than_required_age( $birth_month, $birth_day, $birth_year ) ) {
            // if the user is not old enough to sign up, return false
            return false;
        } else {
            return true;
        }

    }

    /**
     * Sets the default profile picture of the user, depending on if they are male or female.
     */
    private function set_default_profile_picture() {
        // check if the gender is valid before proceeding
        if( !$this->valid_gender ) return;

        if( $this->gender === "male" ) {
            $this->profile_picture = Constant::default_profile_picture_male;
        } else if( $this->gender === "female" ) {
            $this->profile_picture = Constant::default_profile_picture_female;
        } else {
            // just in case (should never happen, though)
            return;
        }
    }

    /**
     * Decides which error message to print for the first name/last name.
     * @return string An error message.
     */
    public function get_name_error_message() {

        if( !$this->valid_firstname && !$this->valid_lastname ) {
            return $this->get_firstname_lastname_error_message();
        } elseif( !$this->valid_firstname ) {
            return $this->get_firstname_error_message();
        } elseif( !$this->valid_lastname ) {
            return $this->get_lastname_error_message();
        }

        // if they are both valid, return nothing
        return "";

    }

    /**
     * Gets the error message to display if the first name is invalid.
     * @return string An error message.
     */
    public function get_firstname_error_message() {

        $error_message = "Please make sure you enter a valid first name.";

        return $error_message;

    }

    /**
     * Gets the error message to display if the last name is invalid.
     * @return string An error message.
     */
    public function get_lastname_error_message() {

        $error_message = "Please make sure you enter a valid last name.";

        return $error_message;

    }

    /**
     * Gets the error message to display if both the first name and last name is invalid.
     * @return string An error message.
     */
    public function get_firstname_lastname_error_message() {

        $error_message = "Please make sure you enter a valid first &amp; last name.";

        return $error_message;

    }

    /**
     * Gets the error message to display if the password is invalid.
     * @return string An error message.
     */
    public function get_password_error_message() {

        // if the password is valid don't return anything
        if( $this->valid_password ) {
            return "";
        }

        $error_message = "Please make sure the passwords match and are longer than 8 characters.";

        return $error_message;

    }

    /**
     * Gets the error message to display if the email is invalid.
     * @return string An error message.
     */
    public function get_email_error_message() {

        // if the email is valid, don't return anything
        if( $this->valid_email ) {
            return "";
        }

        $error_message = "The email " . $this->email . " is invalid or taken.";

        return $error_message;

    }

    /**
     * Gets the error message to display if the gender is invalid.
     * @return string An error message.
     */
    public function get_gender_error_message() {

        // if the gender is valid, don't return anything
        if( $this->valid_gender ) {
            return "";
        }

        $error_message = "Please select a gender.";

        return $error_message;

    }

    /**
     * Gets the error message to display if the birthday is invalid.
     * @return string An error message.
     */
    public function get_birthday_error_message() {

        // if the birthday is valid, don't return anything
        if( $this->valid_birthday ) {
            return "";
        }

        $error_message = "Please make sure you select a valid birthday.";

        return $error_message;

    }

}

?>