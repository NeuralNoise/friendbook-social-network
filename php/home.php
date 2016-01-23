<?php

require "../models/Authorization.php";

$logged_in_user = Authorization::get_logged_in_user();

if( empty( $logged_in_user ) ) {
    include "home_loggedout.php";
    exit();
} else {
    include "home_loggedin.php";
    exit();
}

?>