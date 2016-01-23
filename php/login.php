<?php

require "../models/Login.php";
require "../models/User.php";
require "../models/Conn.php";
require "../models/Authorization.php";
require "../models/UI.php";
require "Constants.php";

$login = new Login( $_POST );

$login_successful = $login->login_successful;

?>

<?php

// redirect the user back to the home page if the login was successful
if( $login_successful ) {
    $login->increment_login_counter();
    $authorization = new Authorization( $login );

    header("Location: home.php");
    exit();
}

?>

<html>
    <head>
        <?php if( !$login_successful ): ?>
            <title>Friendbook - Please fix your login information</title>
        <?php endif ?>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
        <script src="../js/signup.js"></script>
    </head>
    <body>

    <?php echo UI::get_top_bar_logged_out(); ?>

    <div id="container">
        <div class="box-shadow card">
            <div class="bg bold card-separator">
                Please make sure your login information is correct before submitting.
            </div>
            <div class="main-card-content">
                <form id="login-form" action="login.php" method="POST">
                    <?php if( $login->email_valid ): ?>
                        <input type="text" name="login-email" value="<?php echo $login->email; ?>"
                               placeholder="Email" tabindex="1" />
                        <input type="password" name="login-password" placeholder="Password" autofocus tabindex="2" />
                        <input type="submit" value="Log in"  tabindex="3" />
                        <span class="error-message">
                            The password entered is invalid.
                        </span>
                    <?php else: ?>
                        <input type="text" name="login-email" placeholder="Email" autofocus tabindex="1" />
                        <input type="password" name="login-password" placeholder="Password" tabindex="2" />
                        <input type="submit" value="Log in"  tabindex="3" />
                        <span class="error-message">
                            The email entered is invalid.
                        </span>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="box-shadow card">
            <div class="card-separator">
                Did you want to sign up instead?
            </div>
            <div class="bg bold card-separator">
                Sign up to use Friendbook &amp; connect with the people you care about.
            </div>
            <div class="main-card-content">
                <form id="signup-form" action="signup.php" method="POST">
                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Name:</span>
                        <input type="text" name="signup-firstname" placeholder="First name" tabindex="4" />
                        <input type="text" name="signup-lastname" placeholder="Last name" tabindex="5" />
                        <span class="error-message" id="signup-name-error"></span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Password:</span>
                        <input type="password" name="signup-password" placeholder="Desired password" tabindex="6" />
                        <input type="password" name="signup-password-repeat" placeholder="Desired password, again" tabindex="7" />
                        <span class="error-message" id="signup-password-error"></span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Email:</span>
                        <?php if( $login->email_valid ): ?>
                            <input type="text" name="signup-email" value="<?php echo $login->email; ?>"
                                   placeholder="Email" tabindex="8" />
                        <?php else: ?>
                            <input type="text" name="signup-email" placeholder="Email" tabindex="8" />
                        <?php endif; ?>
                        <span class="error-message" id="signup-email-error"></span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Gender:</span>
                        <input type="radio" name="signup-gender" id="male" value="male" tabindex="9" /> Male
                        <input type="radio" name="signup-gender" id="female" value="female" tabindex="10" /> Female
                        <span class="error-message" id="signup-gender-error"></span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Birthday:</span>
                        <select name="signup-month" tabindex="11" >
                            <option value="0" selected="1">Month</option>

                            <?php $birth_months = UI::get_birth_months(); ?>
                            <?php $num_months = count( $birth_months ); ?>

                            <?php for( $month = 0; $month < $num_months; $month++ ): ?>
                                <option value="<?php echo ($month + 1); ?>">
                                    <?php echo $birth_months[$month]; ?>
                                </option>
                            <?php endfor; ?>

                        </select>
                        <select name="signup-day" tabindex="12" >
                            <option value="0" selected="1">Day</option>

                            <?php $birth_days = UI::get_birth_days(); ?>
                            <?php $num_days = count( $birth_days ); ?>

                            <?php for( $day = 0; $day < $num_days; $day++ ): ?>
                                <option value="<?php echo $birth_days[$day]; ?>">
                                    <?php echo $birth_days[$day]; ?>
                                </option>
                            <?php endfor; ?>

                        </select>
                        <select name="signup-year" tabindex="13" >
                            <option value="0" selected="1">Year</option>

                            <?php $birth_years = UI::get_birth_years( 1900, date('Y') ); ?>
                            <?php $num_years = count( $birth_years ); ?>

                            <?php for( $year = $num_years - 1; $year >= 0; $year-- ): ?>
                                <option value="<?php echo $birth_years[$year]; ?>">
                                    <?php echo $birth_years[$year]; ?>
                                </option>
                            <?php endfor; ?>

                        </select>
                        <span class="error-message" id="signup-birthday-error"></span>
                    </div>

                    <div id="signup-form-container">
                        <input type="submit" value="Sign up" tabindex="14" onclick="return validate_signup()" />
                        <b>
                            By signing up for Friendbook, you agree to our
                            <a href="terms_conditions.php" target="_blank" title="View the terms & conditions.">terms &amp; conditions.</a>
                        </b>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </body>
</html>