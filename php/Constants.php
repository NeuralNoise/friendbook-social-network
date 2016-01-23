<?php

/**
 * This file houses all constants that will be used in the Friendbook
 * codebase.
 *
 * I like that: codebase. It makes this project seem huge.
 */

class Constant {

    // the age required to signup for friendbook
    const required_age_to_signup = 13;

    /**
     * The min and max length a name can be.
     */
    const name_min_length = 3;
    const name_max_length = 50;

    /**
     * The min and max length a password can be.
     */
    const password_min_length = 8;
    const password_max_length = 100;

    /**
     * Database information
     */
    const database_name     = "Friendbook";
    const database_server   = "localhost";
    const database_username = "root";
    const database_password = "root";

    // the maximum tries a connection can be made until a connection is successful or
    // the number of tries exceeds the max connection tries
    const max_connection_attempts = 5;

    const user_database_name          = "users";
    const profile_views_database_name = "profile_views";
    const login_count_database_name   = "login_counter";

    /**
     * The default profile pictures for males & females
     */
    const default_profile_picture_male   = "../images/profile/default_male.jpg";
    const default_profile_picture_female = "../images/profile/default_female.jpg";

}

?>