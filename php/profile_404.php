<?php

/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 29-Dec-15
 * Time: 4:21 PM
 */

/**
 * Get the logged in user.
 */
$logged_in_user = Authorization::get_logged_in_user();

/**
 * Get all the info about the user.
 */
$logged_in_user = new User( $logged_in_user["UserID"] );

?>

<html>
    <head>
        <title>
            Friendbook - The user could not be found
        </title>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
    </head>
    <body>
    <?php echo UI::get_top_bar_logged_out(); ?>

    <div id="container">
        <div class="box-shadow card">
            <div class="bold card-separator">
                <h1>The user could not be found.</h1>
            </div>
            <div class="bg card-separator">
                Sorry about that, <b><?php echo $logged_in_user->user_row["Firstname"]; ?></b>.
            </div>
        </div>
        <div class="box-shadow card">
            <div class="main-card-content">
                You could <a href="home.php" class="bold" title="Home">go back home</a>,
                or maybe <?php echo UI::get_HTML_tag("a", array(
                    "href" => "profile.php?uid=" . $logged_in_user->user_row["UserID"],
                    "class" => "bold",
                    "title" => "Visit your own profile",
                ), "visit your own profile"); ?>.
            </div>
        </div>
    </div>
</html>