<?php
/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 24-Nov-15
 * Time: 4:30 PM
 */

require "../models/UI.php";
require "../models/User.php";
require "../models/Conn.php";
require "Constants.php";

?>

<?php

// get the logged in user
$logged_in_user = Authorization::get_logged_in_user();

/**
 * Get the user id of the logged in user.
 */
$logged_in_user_id   = $logged_in_user["UserID"];

/**
 * Get all the info about the user.
 */
$logged_in_user = new User( $logged_in_user_id );

$logged_in_user_firstname       = $logged_in_user->user_row["Firstname"];
$logged_in_user_lastname        = $logged_in_user->user_row["Lastname"];
$logged_in_user_profile_picture = $logged_in_user->user_row["ProfilePicture"];

?>

<html>
    <head>
        <title>Friendbook</title>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
    </head>
    <body>
        <?php echo UI::get_top_bar_logged_out(); ?>

        <div id="container">
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
                                <div class="bottom-poster-info">
                                    <a href="#" title="Edit your profile">Edit your profile</a>
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

                <div class="box-shadow card">
                    <div class="bg card-separator">
                        People on Friendbook
                    </div>
                    <?php /* This is going to be deleted, it's just for testing purposes! */ ?>
                    <?php $id = 1; ?>
                    <?php while(1): ?>
                        <?php $friendbook_user = new User($id); ?>
                        <?php if(!empty($friendbook_user->user_row)): ?>
                            <div class="card-separator">
                                <div class="top-info">
                                    <div class="side-picture">
                                        <img src="<?php echo $friendbook_user->get_profile_picture(); ?>"
                                             class="profile-thumbnail">
                                    </div>
                                    <div class="poster-info">
                                        <div class="top-poster-info">
                                            <?php echo UI::get_profile_link( $friendbook_user ); ?>
                                        </div>
                                        <?php if($friendbook_user->user_row["UserID"] === $logged_in_user_id): ?>
                                            <div class="bottom-poster-info">
                                                <span class="light">This is you</b></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $id += 1; ?>
                        <?php else: ?>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="middle column">
                <div class="box-shadow card">
                    <div class="card-separator">
                        <h1>Stories</h1>
                    </div>
                    <div class="bg card-separator">
                        Viewing <b>most recent</b> stories first
                    </div>
                </div>
                <div class="box-shadow card">
                    <div style="background:#f9f9f9 !important" class="card-separator">
                        <div class="top-info">
                            <span class="icon-container">
                                <img src="../images/Icons/32px/007-pencil2.png" class="icon">
                            </span>
                            <span class="vert-a">
                                <b>Post a status update</b>
                            </span>
                        </div>
                    </div>
                    <div id="post-form-container" class="main-card-content">
                        <form action="post.php" id="post-form">
                            <textarea id="post-textarea" autofocus="true"
                                placeholder="What's on your mind, <?php echo $logged_in_user_firstname; ?>?"></textarea>
                        </form>
                    </div>
                    <div style="background:#f9f9f9 !important" class="main-card-content">
                        <div class="rfloat">
                            <button class="blue-gradient" title="Post to Friendbook">
                                Post
                            </button>
                        </div>
                    </div>
                </div>
                <div id="stories-container">
                    <div class="box-shadow card">
                        <div class="main-card-content">
                            <div class="top-info">
                                <div class="side-picture">
                                    <img src="<?php echo $logged_in_user->get_profile_picture(); ?>"
                                         class="profile-thumbnail">
                                </div>
                                <div class="poster-info">
                                    <div class="top-poster-info">
                                        <?php echo UI::get_profile_link($logged_in_user, "author"); ?>
                                    </div>
                                    <div class="bottom-poster-info">
                                        <span class="light">10 hours ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content">
                                <p>I love Friendbook!</p>
                                <p>It's great!</p>
                            </div>
                        </div>
                        <div class="post-extra card-separator">
                            <div class="post-actions-row">
                                <ul>
                                    <li><a href="#" title="Like this post">Like</a></li>
                                    <li>·</li>
                                    <li><a href="#" title="Comment on this post">Comment</a></li>
                                </ul>
                            </div>
                            <div class="comments-section">
                                <b>0 comments</b> on this post.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right column">
                <?php echo UI::get_story_break("People you may know"); ?>
                <div class="box-shadow card">
                    <div class="main-card-content">
                    </div>
                </div>

                <?php echo UI::get_footer(); ?>
            </div>
        </div>
    </body>
</html>