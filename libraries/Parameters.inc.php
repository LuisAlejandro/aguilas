<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die("ERROR");
}

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";

// For each $_POST parameter, check if it is an allowed operation
// and remove unwanted characters
foreach( $_POST as $key => $value ){
    if( in_array( $key, $allowed_ops )){
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = stripslashes($value);
        $$key = "$value";
    }
}

// For each $_GET parameter, check if it is an allowed operation
// and remove unwanted characters
foreach( $_GET as $key => $value ){
    if( in_array( $key, $allowed_ops )){
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = stripslashes($value);
        $$key = "$value";
       }
}

// We store today's date
$time_today = date("d-m-Y-H:i:s");

// Generate confirmation token to send by e-mail
$token = md5(mt_rand() . "-" . time() . "-" . $_SERVER['REMOTE_ADDR'] . "-" . mt_rand());

// A little description
$description = _("POWEREDBY") . $app_name . _("AT") . $time_today . _("PETITION") . $_SERVER['REMOTE_ADDR'];

// Starting session (cookies)
session_start();
// We get the md5 hash from the cookie
// If it's not there, then the cookie expired
if (isset($_SESSION['captcha'])) {
    $session_captcha = $_SESSION['captcha'];
}
// We get the captcha code fom user input anf hash it
if (isset($image_captcha)){
    $image_captcha = md5($image_captcha);
}

?>
