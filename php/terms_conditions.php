<?php

require "../models/UI.php";
require "../php/Constants.php";

?>

<html>
    <head>
        <title>Friendbook - Terms & Conditions</title>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
    </head>
    <body>
        <?php echo UI::get_top_bar_logged_out(); ?>
        <div id="container">

            <div class="card">

                <div class="bg uppercase bold card-separator">
                    Terms &amp; Conditions.
                </div>

                <div class="main-card-content">
                    You must be at least <b><?php echo Constant::required_age_to_signup; ?> years old</b> to create a Friendbook profile.
                </div>

            </div>

        </div>
    </body>