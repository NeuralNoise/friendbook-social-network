<?php
/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 09-Dec-15
 * Time: 11:11 PM
 */

/**
 * This script checks if a user is logged in to Friendbook. If no user is logged
 * in, then the user is redirected back to home.
 */

require "../models/Authorization.php";

$logged_in_user = Authorization::get_logged_in_user();

if( empty( $logged_in_user ) ) {
    header("Location: home.php");
    exit();
}

?>