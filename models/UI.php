<?php

/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 26-Nov-15
 * Time: 10:40 PM
 */
class UI {

    /**
     * Gets the top bar containing the Friendbook logo.
     * @return string The HTML of the top bar to be displayed.
     */
    public static function get_top_bar_logged_out() {
        $top_bar  = '<div id="page-header">';
        $top_bar .= '<div id="logo-container">';
        $top_bar .= '<span class="logo">';
        $top_bar .= '<a href="home.php" title="Home">';
        $top_bar .= 'Friendbook';
        $top_bar .= '</a>';
        $top_bar .= '</span>';
        $top_bar .= '</div>';
        $top_bar .= '</div>';

        return $top_bar;
    }

    /**
     * Gets the card containing the log in fields for the user.
     * @return string The card containing the log in fields.
     */
    public static function get_login_card() {
        $login_card  = '<div class="box-shadow card">';
        $login_card .= '<div class="bg bold card-separator">';
        $login_card .= 'Log in to Friendbook.';
        $login_card .= '</div>';
        $login_card .= '<div class="main-card-content">';
        $login_card .= 'Welcome back.';
        $login_card .= '<form id="login-form" action="login.php" method="POST">';
        $login_card .= ' <input type="text" name="login-email" placeholder="Email" autofocus tabindex="1" />';
        $login_card .= '<input type="password" name="login-password" placeholder="Password" tabindex="2" />';
        $login_card .= '<input type="submit" value="Log in"  tabindex="3" />';
        $login_card .= '</form>';
        $login_card .= '</div>';
        $login_card .= '</div>';

        return $login_card;
    }

    /**
     * Gets all the birth months (January .. December).
     * @return array An array containing the birth months.
     */
    public static function get_birth_months() {
        $birth_months = array(
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        );

        return $birth_months;
    }

    /**
     * Gets the days of birth (1 .. 31).
     * @return array An array containing the days of the months.
     */
    public static function get_birth_days() {
        $birth_days = array();

        for($birth_day = 1; $birth_day <= 31; $birth_day++) {
            $birth_days[] = $birth_day;
        }

        return $birth_days;
    }

    /**
     * Gets all years in a range specified.
     * @param int $from The year to start from.
     * @param int $to The year to count to.
     * @return array Birth years in that range.
     */
    public static function get_birth_years( $from, $to ) {
        $birth_years = array();

        for($year = $from; $year <= $to; $year++) {
            $birth_years[] = $year;
        }

        return $birth_years;
    }

    /**
     * Gets the footer.
     * @return string The HTML code for the footer.
     */
    public static function get_footer() {
        $footer  = '<div class="box-shadow card">';
        $footer .= '<div class="card-separator">';
        $footer .= 'Friendbook &copy; <b>' . date('Y') . '</b>';
        $footer .= '</div>';
        $footer .= '</div>';

        return $footer;
    }

    /**
     * Returns a plural form of a word, based on the "amount".
     * @param $singular string The singular form of the word.
     * @param $plural string The plural form of the word.
     * @param $amount int The amount of the "stuff".
     * @return string The plural/singular form of the word.
     */
    public static function pluralize( $singular, $plural, $amount ) {
        return $amount == 1 ? $singular : $plural;
    }

    /**
     * Returns HTML code for a break in the story stream.
     * @param $title string The title of the story break.
     * @return string The HTML code for the story break.
     */
    public static function get_story_break( $title ) {
        $story_break  ='<div class="uppercase stories-title">';
        $story_break .= $title;
        $story_break .= '</div>';

        return $story_break;
    }

    /**
     * Gets the HTML for the <a> tag linking to the user id profile.
     * @param $user User The user object containing info about the user.
     * @return string The HTML code for the <a> tag.
     */
    public static function get_profile_link( $user, $class="dark-link author" ) {
        $uid       = $user->user_row["UserID"];
        $firstname = $user->user_row["Firstname"];

        $profile_link  = '<a href="profile.php?uid=' . $uid .'" ';
        $profile_link .= 'title="View ' . $firstname . '\'s profile" ';
        $profile_link .= 'class="' . $class . '" target="_blank" >';
        $profile_link .= $user;
        $profile_link .= "</a>";

        return $profile_link;
    }

    /**
     * Creates an HTML tag.
     * @param $tag string The tag to create (a, div, etc.)
     * @param $attributes array The attributes of the tag.
     * Should be in the following format:
     *      array("class" => "test", "..." => "...", ...)
     * @param $contents string The "contents" of the tag. Basically what's inside the closing and
     * opening tag.
     * @return string The HTML tag.
     */
    public static function get_HTML_tag( $tag, $attributes=array(), $contents="" ) {
        $HTMLtag = "<" . $tag;

        foreach($attributes as $attribute => $value) {
            $HTMLtag .= ' ' . $attribute . '="' . $value . '"';
        }

        $HTMLtag .= ">";

        return $contents === "" ? $HTMLtag : $HTMLtag . $contents . "</" . $tag . ">";
    }

    /**
     * Checks the gender of the user, and determines whether to use "him" or "her" to refer to the user.
     * @param $user User The user whose gender to check.
     * @return string "him" or "her"
     */
    public static function get_him_her_pronoun( $user ) {
        $user_gender = $user->get_gender();
        return $user_gender === "male" ? "him" : "her";
    }

    /**
     * Checks the gender of the user, and determines whether to use "he" or "she" to refer to the user.
     * @param $user User The user whose gender to check.
     * @return string "he" or "she"
     */
    public static function get_he_she_pronoun( $user ) {
        $user_gender = $user->get_gender();
        return $user_gender === "male" ? "he" : "she";
    }

    /**
     * Checks the gender of the user, and determins whether to use "his" or "her" to refer to the user.
     * @param $user User The user whose gender to check.
     * @return string "his" or "her"
     */
    public static function get_his_her_pronoun( $user ) {
        $user_gender = $user->get_gender();
        return $user_gender === "male" ? "his" : "her";
    }

    /**
     * Capitalizes the given word. Example: "dOg"->"Dog"
     * @param $word string The word to capitalize.
     * @return string The capitalized word.
     */
    public static function capitalize( $word ) {
        return ucfirst(strtolower($word));
    }

    /**
     * Truncates a word to a specified length.
     * @param $word string The word to truncate.
     * @param $max_length int The length to truncate the word at.
     * @return array The original word, as well as the truncated word.
     */
    public static function truncate_word($word, $max_length) {
        $word_length = strlen($word);
        $truncated_word = "";
        for( $index = 0; $index < $max_length; $index++ ) {
            // break from the loop so as to avoid a seg fault
            if($index >= $word_length) break;
            $truncated_word .= $word[$index];
        }

        return array($word, $truncated_word);
    }

}

?>