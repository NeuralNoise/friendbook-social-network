<?php

/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 19-Nov-15
 * Time: 12:28 AM
 */
class Authorization {

    /**
     * @param $login Login A login object. A session will be created with information
     * from this login object.
     */
    public function __construct( $login ) {

        $this->destroy_session();

        // start a session to store stuff inside a session variable
        $this->start_session();

        /**
         * Get all the needed info from the login object and put it in a session.
         */
        $_SESSION["UserID"]    = $login->user_row[0]["UserID"];

    }

    /**
     * Starts a session if a session isn't already active.
     */
    private function start_session() {
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
    }

    /**
     * Destroys the active session.
     */
    private function destroy_session() {
        $this->start_session();

        $_SESSION = array();

        session_destroy();
    }

    /**
     * Gets the logged in user.
     * @return array An array containing information about the logged in user.
     */
    public static function get_logged_in_user() {
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

        if( isset( $_SESSION["UserID"] ) ) {
            return $_SESSION;
        } else {
            return array();
        }
    }

    /**
     * Destroys the session and logs the user out.
     */
    public static function logout_user() {
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

        $_SESSION = array();

        session_destroy();
    }

}