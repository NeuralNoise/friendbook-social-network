<?php

require "../models/Signup.php";
require "../models/User.php";
require "../models/Conn.php";
require "../models/UI.php";
require "Constants.php";

$signup = new Signup( $_POST );

$signup_successful = $signup->valid_signup;

?>

<html>
    <head>
        <?php if( $signup_successful ): ?>
        <title>Friendbook - Thanks for signing up!</title>
        <?php else: ?>
        <title>Friendbook - Please fix these errors before proceeding</title>
        <?php endif ?>
        <link type="text/css" rel="stylesheet" href="../style/home.css">
        <script src="../js/signup.js"></script>
    </head>
    <body>

    <?php echo UI::get_top_bar_logged_out(); ?>

    <div id="container">

        <div class="box-shadow card">
            <div class="bold bg card-separator">
                <?php if( $signup_successful ): ?>
                    <?php

                    // create the user in the database
                    if( User::create_user( $signup ) ) {
                        echo "Thanks for signing up for Friendbook, " . $signup->firstname . ".";
                    } else {
                        $signup_successful = false;
                        echo "We have encountered a problem on our side. Sorry about that.";
                    }

                    ?>
                <?php else: ?>
                    There were some errors signing you up. Please fix them to proceed.
                <?php endif; ?>
            </div>
            <div class="main-card-content">

                <?php if( $signup_successful ): ?>
                    You may now <a href="home.php">log in to Friendbook</a> and begin connecting with your friends &amp; family.
                    <?php

                    // reset the array in to prevent multiple submissions
                    unset($_POST);

                    ?>
                <?php else: ?>
                    <form id="signup-form" action="../php/signup.php" method="POST">
                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Name:</span>
                        <?php if( $signup->valid_firstname ): ?>
                            <input type="text" name="signup-firstname" value="<?php echo $signup->firstname; ?>"
                                   placeholder="First name" tabindex="4" />
                        <?php else: ?>
                            <input type="text" name="signup-firstname" placeholder="First name" tabindex="4" />
                        <?php endif; ?>

                        <?php if( $signup->valid_lastname ): ?>
                            <input type="text" name="signup-lastname" value="<?php echo $signup->lastname; ?>"
                                   placeholder="Last name" tabindex="5" />
                        <?php else: ?>
                            <input type="text" name="signup-lastname" placeholder="Last name" tabindex="5">
                        <?php endif; ?>

                        <span class="error-message" id="signup-name-error">
                            <?php echo $signup->get_name_error_message(); ?>
                        </span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Password:</span>
                        <?php if( $signup->valid_password ): ?>
                            <input type="password" name="signup-password" placeholder="Desired password" tabindex="6" />
                            <input type="password" name="signup-password-repeat" placeholder="Desired password, again" tabindex="7" />
                            The password was fine. Please enter it again, though.
                        <?php else: ?>
                            <input type="password" name="signup-password" placeholder="Desired password" tabindex="6" />
                            <input type="password" name="signup-password-repeat" placeholder="Desired password, again" tabindex="7" />
                        <?php endif; ?>

                        <span class="error-message" id="signup-password-error">
                            <?php echo $signup->get_password_error_message(); ?>
                        </span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Email:</span>
                        <?php if( $signup->valid_email ): ?>
                            <input type="text" name="signup-email" value="<?php $signup->email?>"
                                   placeholder="Email" tabindex="8" />
                        <?php else: ?>
                            <input type="text" name="signup-email" placeholder="Email" tabindex="8" />
                        <?php endif; ?>

                        <span class="error-message" id="signup-email-error">
                            <?php echo $signup->get_email_error_message(); ?>
                        </span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Gender:</span>
                        <?php if( $signup->valid_gender && $signup->gender === "male" ): ?>
                            <input type="radio" name="signup-gender" id="male" value="male" tabindex="9" checked /> Male
                            <input type="radio" name="signup-gender" id="female" value="female" tabindex="10" /> Female
                        <?php elseif( $signup->valid_gender && $signup->gender === "female" ): ?>
                            <input type="radio" name="signup-gender" id="male" value="male" tabindex="9" /> Male
                            <input type="radio" name="signup-gender" id="female" value="female" tabindex="10" checked /> Female
                        <?php else: ?>
                            <input type="radio" name="signup-gender" id="male" value="male" tabindex="9" /> Male
                            <input type="radio" name="signup-gender" id="female" value="female" tabindex="10" /> Female
                        <?php endif; ?>
                        <span class="error-message" id="signup-gender-error">
                            <?php echo $signup->get_gender_error_message(); ?>
                        </span>
                    </div>

                    <div id="signup-form-container">
                        <span style="font-weight:bold; margin-right:5px;">Birthday:</span>
                        <select name="signup-month" tabindex="11" >
                            <option value="0">Month</option>

                            <?php

                            $birth_months = get_birth_months();
                            for($i = 0; $i < count($birth_months); $i++) {
                                if( ($i + 1) == $signup->birth_month && $signup->valid_birthday ) {
                                    echo '<option value="' . ($i + 1) . '" selected>' . $birth_months[$i] . '</option>';
                                } else {
                                    echo '<option value="' . ($i + 1) . '">' . $birth_months[$i] . '</option>';
                                }
                            }

                            ?>
                        </select>
                        <select name="signup-day" tabindex="12" >
                            <option value="0">Day</option>

                            <?php

                            $birth_days = get_birth_days();
                            for($i = 0; $i < count($birth_days); $i++) {
                                if( $i + 1 == $signup->birth_day && $signup->valid_birthday ) {
                                    echo '<option value="' . $birth_days[$i] . '" selected>' . $birth_days[$i] . '</option>';
                                } else{
                                    echo '<option value="' . $birth_days[$i] . '">' . $birth_days[$i] . '</option>';
                                }
                            }

                            ?>
                        </select>
                        <select name="signup-year" tabindex="13" >
                            <option value="0">Year</option>

                            <?php

                            $birth_years = get_birth_years(1900, date('Y'));
                            for($i = count($birth_years) - 1; $i >= 0; $i--) {
                                if( $birth_years[$i] == $signup->birth_year && $signup->valid_birthday ) {
                                    echo '<option value="' . $birth_years[$i] . '" selected>' . $birth_years[$i] . '</option>';
                                } else {
                                    echo "i = " . $i . ", birth_year = " . $signup->birth_year . ".";
                                    echo '<option value="' . $birth_years[$i] . '">' . $birth_years[$i] . '</option>';
                                }
                            }

                            ?>
                        </select>
                        <span class="error-message" id="signup-birthday-error">
                            <?php echo $signup->get_birthday_error_message(); ?>
                        </span>
                    </div>

                    <div id="signup-form-container">
                        <input type="submit" value="Sign up" tabindex="14" onclick="return validate_signup()" />
                        <b>
                            By signing up for Friendbook, you agree to our
                            <a href="terms_conditions.php" target="_blank" title="Read the terms & conditions.">terms &amp; conditions.</a>
                        </b>
                    </div>
                </form>
                <?php endif; ?>

            </div>
        </div>

    </div>

    </body>
</html>