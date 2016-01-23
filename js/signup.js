// the min and max length a first or last name can be
const name_min_length = 3;
const name_max_length = 50;

// the min and max length a password can be
const password_min_length = 8;
const password_max_length = 100;

// the age the person has to be to be able to signup for Friendbook
const required_age = 13;

/**
 * @return boolean makes sure the form is valid: returns true if it is,
 * false if it isn't
 */
function validate_signup() {

    // a boolean value of whether the first name is valid or not
    var valid_firstname = firstname_is_valid();

    // a boolean value of whether the last name is valid or not
    var valid_lastname = lastname_is_valid();

    // a boolean value of whether the password is valid or not
    var valid_password = password_is_valid();

    // a boolean value of whether the email is valid or not
    var valid_email = email_is_valid();

    // a boolean value of whether the gender is valid or not
    var valid_gender = gender_is_valid();

    var valid_birthday = birthday_is_valid();

    write_errors(valid_firstname, valid_lastname, valid_password, valid_email, gender_is_valid(), valid_birthday);

    return valid_firstname && valid_lastname && valid_password && valid_email && valid_gender && valid_birthday;
}

/**
 * @return boolean checks if a first name is given, and if it is checks
 * if it's less than 20 characters and has no invalid characters
 */
function firstname_is_valid() {

    // get the first name from the page
    var firstname = document.getElementById("signup-form").elements.namedItem("signup-firstname").value;

    // if the first name is empty, then just return false
    if(firstname === "") {
        return false;
    }

    // get the length of the first name
    var firstname_length = firstname.length;

    // if the first name is longer than the max it can be, return false
    if(firstname_length < name_min_length || firstname_length > name_max_length) {
        return false;
    }

    // the pattern to find in the first name
    var patt = new RegExp(/[^a-z ]/i);

    // if the pattern is found, the first name is invalid
    if(patt.test(firstname)) {
        return false;
    }

    // all tests passed, so return true
    return true;
}

/**
 * @return boolean checks if a last name is given, and if it is checks
 * if it's less than 20 characters and has no invalid characters
 */
function lastname_is_valid() {

    // get the first name from the page
    var lastname = document.getElementById("signup-form").elements.namedItem("signup-lastname").value;

    // if the last name is empty, then just return false
    if(lastname === "") {
        return false;
    }

    // get the length of the last name
    var lastname_length = lastname.length;

    // if the last name is longer than the max it can be, return false
    if(lastname_length < name_min_length || lastname_length > name_max_length) {
        return false;
    }

    // the pattern to find in the last name
    var patt = new RegExp(/[^a-z ]/i);

    // if the pattern is found, the first name is invalid
    if(patt.test(lastname)) {
        return false;
    }

    // all tests passed, so return true
    return true;
}

/**
 * @return boolean checks if a password is given, and if it is checks if both passwords match,
 * and if they are a certain length
 */
function password_is_valid() {

    // gets the password
    var password = document.getElementById("signup-form").elements.namedItem("signup-password").value;

    // gets the repeated password
    var password_repeat = document.getElementById("signup-form").elements.namedItem("signup-password-repeat").value;

    // if the passwords are empty then return false
    if(password === "" || password_repeat === "") {
        return false;
    }

    // if the passwords don't match, then return false
    if(password !== password_repeat) {
        return false;
    }

    // the length of the password
    var password_length = password.length;

    // if the password length is outside that range, then return false
    if(password_length < password_min_length || password_length > password_max_length) {
        return false;
    }

    // all tests passed, so return true
    return true;
}

/**
 * @return boolean checks if the email is valid, and if it is returns a true value
 */
function email_is_valid() {

    // get the email from the form
    var email = document.getElementById("signup-form").elements.namedItem("signup-email").value;

    // if no email is given, just return false
    if(email === "") {
        return false;
    }

    // the regex to use to check if the email is given is an actual email
    var patt = new RegExp(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i);

    // if the pattern is not found, the email address is invalid
    if(!patt.test(email)) {
        return false;
    }

    // all tests passed, so return true
    return true;
}

/**
 * @return boolean checks if any of the radio buttons are checked
 */
function gender_is_valid() {

    // a boolean value to check whether the male radio button is selected
    var male_is_checked = document.getElementById("male").checked;

    // a boolean value to check whether the female radio button is selected
    var female_is_checked = document.getElementById("female").checked;

    // if both are unchecked, return false
    if(!male_is_checked && !female_is_checked) {
        return false;
    }

    // all test passed, so return true
    return true;
}

/**
 * @return boolean checks if the birthday entered is valid (valid mm/dd/yy combination) - not like
 * February 31 or something
 */
function birthday_is_valid() {

    // get the dropdown menus
    var dropdown_month = document.getElementById("signup-form").elements.namedItem("signup-month");
    var dropdown_day = document.getElementById("signup-form").elements.namedItem("signup-day");
    var dropdown_year = document.getElementById("signup-form").elements.namedItem("signup-year");

    // get the month, day, and year from the dropdown menu
    var month = parseInt(dropdown_month.options[dropdown_month.selectedIndex].value);
    var day = parseInt(dropdown_day.options[dropdown_day.selectedIndex].value);
    var year = parseInt(dropdown_year.options[dropdown_year.selectedIndex].value);

    // if no month, day, or year is given, then just return false
    if(month === 0 || day === 0 || year === 0) {
        return false;
    }

    // if the month, day, year combination is not valid return false
    if(!month_day_year_combination_is_valid(month, day, year)) {
        return false;
    }

    // if the user is too young to make an account, return false
    if(!older_than_required_age(month, day, year, required_age)) {
        return false;
    }

    // all tests passed, so return true
    return true;
}

/**
 * @param birth_month int The month the person was born on
 * @param birth_day int The day the person was born on
 * @param birth_year int The year the person was born on
 * @param required_age int The age the person has to be to sign up
 * @return boolean Whether the person is of valid age
 */
function older_than_required_age(birth_month, birth_day, birth_year, required_age) {

    // get the current month, day, and year
    var date = new Date();
    // add 1 because getMonth gives months from 0 - 11
    var current_month = date.getMonth() + 1;
    var current_day = date.getDate();
    var current_year = date.getFullYear();

    // the age of the user
    var age = current_year - birth_year;

    if(current_month === birth_month && current_day < birth_day) {
        age -= 1;
    } else if(current_month < birth_month) {
        age -= 1;
    }

    if(age >= required_age) {
        // alert a friendly happy birthday message if it's the user's birthday
        if(birth_month === current_month && birth_day === current_day) {
            alert("Thanks for signing up for Friendbook on your birthday. Happy birthday!");
        }

        return true;
    } else {
        return false;
    }
}

/**
 * @param month int The month to check
 * @param day int the day to check
 * @param year int the year to check
 * @return boolean Returns a boolean value if the combination is valid: not something like
 * February 30 for example
 */
function month_day_year_combination_is_valid(month, day, year) {

    switch(month) {
        case 1:
            // January
            return day <= 31;

        case 2:
            // February
            // check if the year is a leap year
            if(year % 4 === 0 && year % 100 === 0 && year % 400 === 0) {
                // there are 29 days in leap years
                return day <= 29;
            } else {
                // there are only 28 days otherwise
                return day <= 28;
            }

        case 3:
            // March
            return day <= 31;

        case 4:
            // April
            return day <= 30;

        case 5:
            // May
            return day <= 31;

        case 6:
            // June
            return day <= 30;

        case 7:
            // July
            return day <= 31;

        case 8:
            // August
            return day <= 31;

        case 9:
            // September
            return day <= 30;

        case 10:
            // October
            return day <= 31;

        case 11:
            // November
            return day <= 30;

        case 12:
            // December
            return day <= 31;

        default:
            return false;
    }
}

/**
 * This function writes the errors to the signup page
 * @param firstname_is_valid boolean whether the first name is valid or not
 * @param lastname_is_valid boolean whether the last name is valid or not
 * @param password_is_valid boolean whether the password is valid or not
 * @param email_is_valid boolean whether the email is valid or not
 * @param gender_is_valid boolean whether the gender is valid or not
 * @param birthday_is_valid boolean whether the birthday is valid or not
 */
function write_errors(
    firstname_is_valid,
    lastname_is_valid,
    password_is_valid,
    email_is_valid,
    gender_is_valid,
    birthday_is_valid) {

    // the message to display if both the first name and the last name are invalid
    var firstname_lastname_error_message = "Please make sure you enter a valid first & last name.";

    // the message to display if only the first name is invalid
    var firstname_error_message = "Please make sure you enter a valid first name.";

    // the message to display if only the last name is invalid
    var lastname_error_message = "Please make sure you enter a valid last name.";

    // the message to display if the password is invalid
    var password_error_message = "Please make sure the passwords match and are longer than ";
    password_error_message    += password_min_length;
    password_error_message    += " characters.";

    // the message to display if the email is invalid
    var email_error_message = "Please make sure your email is valid.";

    var gender_error_message = "Please select a gender.";

    var birthday_error_message = "Please make sure you select a valid birthday.";

    if(!firstname_is_valid && !lastname_is_valid) {
        document.getElementById("signup-name-error").textContent = firstname_lastname_error_message;
    } else if(!firstname_is_valid) {
        document.getElementById("signup-name-error").textContent = firstname_error_message;
    } else if(!lastname_is_valid) {
        document.getElementById("signup-name-error").textContent = lastname_error_message;
    } else {
        document.getElementById("signup-name-error").textContent = "";
    }

    if(!password_is_valid) {
        document.getElementById("signup-password-error").textContent = password_error_message;
    } else {
        document.getElementById("signup-password-error").textContent = "";
    }

    if(!email_is_valid) {
        document.getElementById("signup-email-error").textContent = email_error_message;
    } else {
        document.getElementById("signup-email-error").textContent = "";
    }

    if(!gender_is_valid) {
        document.getElementById("signup-gender-error").textContent = gender_error_message;
    } else {
        document.getElementById("signup-gender-error").textContent = "";
    }

    if(!birthday_is_valid) {
        document.getElementById("signup-birthday-error").textContent = birthday_error_message;
    } else {
        document.getElementById("signup-birthday-error").textContent = "";
    }
}