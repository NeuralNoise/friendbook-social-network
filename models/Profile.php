<?php

/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 29-Dec-15
 * Time: 5:58 AM
 */
class Profile {

    /**
     * @var User The user object pertaining to this profile.
     */
    public $viewed_user;

    /**
     * @var User The user viewing the profile.
     */
    public $viewer_user;

    /**
     * @var bool Whether the profile is valid (from the GET ID).
     */
    public $valid_profile;

    /**
     * The constructor for the profile object.
     * @param $user
     */
    public function __construct( $GET_DATA ) {
        $this->valid_profile = isset( $GET_DATA );
    }

    /**
     * Set the member variables for the viewer and the viewed users.
     * @param $viewer_user User The user viewing the profile.
     * @param $viewed_user User The user whose profile is being viewed.
     */
    public function set_users( $viewer_user, $viewed_user ) {
        $this->viewer_user = $viewer_user;
        $this->viewed_user = $viewed_user;
    }

    /**
     * Gets the page title of the profile page.
     * @return string The title of the page.
     */
    public function get_profile_title() {
        return $this->viewed_user . " on Friendbook";
    }

    /**
     * Increments the number of times a profile has been viewed in the database.
     * Yay storing user data!
     */
    public function increment_profile_view_counter() {
        $viewer_id = ($this->viewer_user->user_row["UserID"]);
        $viewed_id = ($this->viewed_user->user_row["UserID"]);

        // God I hate these sql queries
        $sql_query  = 'INSERT INTO ' . Constant::profile_views_database_name . ' VALUES ';
        $sql_query .= '('. $viewer_id . ', ' . $viewed_id . ', 1)';
        $sql_query .= ' ON DUPLICATE KEY UPDATE `ProfileViews` = `ProfileViews` + 1;';

        $connection = create_connection();

        // process the query
        process_query($sql_query, $connection);

        close_connection($connection);
    }

    /**
     * Checks if the user is viewing his/her own profile.
     * @return bool Whether the user is viewing his/her own profile.
     */
    public function viewing_own_profile() {
        return User::users_are_the_same($this->viewed_user, $this->viewer_user);
    }

}

?>