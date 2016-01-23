<?php
/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 09-Dec-15
 * Time: 11:10 PM
 */

require "verify_logged_in_user.php";
require "../models/User.php";
require "../models/Conn.php";
require "../models/UI.php";
require "../models/Profile.php";
require "Constants.php";

/**
 * Get the logged in user.
 */
$logged_in_user = Authorization::get_logged_in_user();

/**
 * Get all the info about the user.
 */
$logged_in_user = new User( $logged_in_user["UserID"] );

$profile = new Profile( $_GET );

if(!isset($_GET["uid"])) {
    require "profile_404.php";
    exit();
}

$viewed_user = new User( $_GET["uid"] );

/**
 * If the user with the user ID does not exist, show the 404 page.
 */
if( empty( $viewed_user->user_row ) ) {
    require "profile_404.php";
    exit();
}

$profile->set_users($logged_in_user, $viewed_user);

// increment the profile view counter
// $profile->increment_profile_view_counter();

?>

<html>
    <head>
        <title>
            <?php echo $profile->get_profile_title(); ?>
        </title>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
    </head>
    <body>
    
    <?php echo UI::get_top_bar_logged_out(); ?>

    <div class="top-container">
        <div id="profile-intro-card" class="card">
            <div class="card-separator">
                <div class="top-info">
                    <div class="side-picture">
                        <img src="<?php echo $viewed_user->get_profile_picture(); ?>" class="large profile-thumbnail">
                    </div>
                    <div class="poster-info">
                        <div class="top-poster-info">
                            <h1><?php echo $viewed_user; ?></h1>
                        </div>
                        <div class="bottom-poster-info">
                            <span style="font-size:15px !important;">
                                Tagline should go here
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($profile->viewing_own_profile()): ?>
                <div class="card-separator">
                    <a href="#">Edit your profile</a>
                </div>
            <?php else: ?>
                <div class="card-separator">
                    <form id="profile-actions">
                        <button class="add-friend" type="submit"
                                title="Add <?php echo UI::get_him_her_pronoun($viewed_user); ?> as a friend">
                            <span class="icon-container">
                                <img src="../images/Icons/32px/116-user-plus.png" class="button-icon">
                            </span>
                            <span class="vert-a">
                                Add Friend
                            </span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="tab-container card-separator">
                <div id="profile-tabs">
                    <ul class="tabs">
                        <li class="selected tab">
                            <a href="#">Timeline</a>
                        </li>
                        <li class="tab">
                            <a href="#">About</a>
                        </li>
                        <li class="tab">
                            <a href="#">Friends</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="no-top" id="container">
        <?php if(!$profile->viewing_own_profile()): ?>
            <div class="box-shadow card">
                <div class="bg uppercase card-separator">
                    Do you know <?php echo $viewed_user->get_first_name(); ?>?
                </div>
                <div class="card-separator">
                    <span class="light">
                        If so, add <?php echo UI::get_him_her_pronoun($viewed_user); ?> as a friend.
                    </span>
                    <div class="rfloat">
                        <button class="blue-gradient"
                                title="Add <?php echo UI::get_him_her_pronoun($viewed_user); ?> as a friend">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="left column">
            <div class="box-shadow card">
                <div class="card-separator main-card-content">
                    <div class="top-info">
                        <div class="side-picture">
                            <img src="<?php echo $logged_in_user->get_profile_picture(); ?>"
                                 class="profile-thumbnail">
                        </div>
                        <div class="poster-info">
                            <div class="top-poster-info">
                                <?php echo UI::get_profile_link($logged_in_user); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-separator">
                    <span class="icon-container">
                        <img src="../images/Icons/32px/277-exit.png" class="icon">
                    </span>
                    <span class="vert-a">
                        <a href="logout.php" title="Log out of Friendbook">Log out</a>
                    </span>
                </div>
            </div>
            <div class="box-shadow card">
                <div class="card-separator">
                        <span class="icon-container">
                            <img src="../images/Icons/32px/027-bullhorn.png" class="icon">
                        </span>
                        <span class="vert-a">
                            <b>0</b> <?php echo UI::pluralize("notification", "notifications", 0); ?>
                            <span class="rfloat">
                                <a href="#" class="bold dark-link">View</a>
                            </span>
                        </span>
                </div>
                <div class="card-separator">
                        <span class="icon-container">
                            <img src="../images/Icons/32px/391-mail4.png" class="icon">
                        </span>
                        <span class="vert-a">
                            <b>0</b> <?php echo UI::pluralize("message", "messages", 0); ?>
                            <span class="rfloat">
                                <a href="#" class="bold dark-link">View</a>
                            </span>
                        </span>
                </div>
                <div class="card-separator">
                        <span class="icon-container">
                            <img src="../images/Icons/32px/115-users.png" class="icon">
                        </span>
                        <span class="vert-a">
                            <b>0</b> friend <?php echo UI::pluralize("request", "requests", 0); ?>
                            <span class="rfloat">
                                <a href="#" class="bold dark-link">View</a>
                            </span>
                        </span>
                </div>
            </div>
        </div>
        <div class="middle column">
            <div id="stories-container">
                <div class="box-shadow card">
                    <div class="card-separator">
                        <h1>Stories by <?php echo $viewed_user->get_first_name(); ?></h1>
                    </div>
                    <div class="bg card-separator">
                        Viewing <b><?php echo UI::get_his_her_pronoun($viewed_user); ?> most recent</b> stories first
                    </div>
                </div>
            </div>
        </div>
        <div class="right column">
            <?php echo UI::get_story_break("Basic information"); ?>
            <div class="box-shadow card">
                <div class="card-separator">
                    Gender
                    <div class="bold rfloat">
                        <?php echo UI::capitalize($viewed_user->get_gender()); ?>
                    </div>
                </div>
                <div class="card-separator">
                    Email
                    <?php

                    $truncated_and_original_email = UI::truncate_word($viewed_user->get_email(), 13);

                    $original_email  = $truncated_and_original_email[0];
                    $truncated_email = $truncated_and_original_email[1];

                    ?>
                    <div class="bold rfloat"
                        <?php if($truncated_email !== $original_email) echo ' title="' . $original_email . '"'?>>
                        <?php if($truncated_email !== $original_email): ?>
                            <?php echo $truncated_email . '...'; ?>
                        <?php else: ?>
                            <?php echo $truncated_email; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-separator">
                    Age
                    <div class="bold rfloat"
                        title="Born on <?php echo $viewed_user->get_date_of_birth(); ?>">
                        <?php echo $viewed_user->get_age(); ?> years old
                    </div>
                </div>
            </div>
        </div>
    </div>
</html>