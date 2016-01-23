<?php

require "../models/Authorization.php";

// destroys the session thus logging the user out.
Authorization::logout_user();

// redirect the user back to the home page.
header("Location: home.php");
exit();

?>