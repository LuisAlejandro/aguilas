<?php


foreach( $_POST as $key => $value ){
    if( in_array( $key, $allowed_ops )){
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = stripslashes($value);
        $$key = "$value";
    }
}

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

?>
