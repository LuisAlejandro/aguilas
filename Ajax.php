<?php

$allowed_ops = array("obj_a", "val_a", "who_a", "cn_a");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./libraries/LDAPConnection.inc.php";

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

// USER INPUT VALIDATION ------------------------------------------------------- 
// Someone tried to pass other parameter than $obj_a, $val_a,
// $who_a or $cn_a
if (!isset($obj_a) || !isset($val_a) || !isset($who_a) || !isset($cn_a)) {

    echo _("Hey! What are you trying to do?");

// One or more of the fields was left empty
} elseif ($obj_a == '' || $val_a == '' || $who_a == '' || $cn_a == '') {

    echo _("You cannot leave empty fields.");

// The first name has improper characters
} elseif ($obj_a == "givenName" && preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $val_a) == 0) {

    echo _("Your first name has invalid characters. You can only use letters (uppercase, lowercase), accented letters or with umlaut.");

// The first name has more than 60 characters
} elseif ($obj_a == "givenName" && (strlen($val_a) > 60)) {

    echo _("Your first name is longer than 60 characters.");

// The last name has improper characters
} elseif ($obj_a == "sn" && preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $val_a) == 0) {

    echo _("Your last name has invalid characters. You can only use letters (uppercase, lowercase), accented letters or with umlaut.");

// The last name has more than 60 characters
} elseif ($obj_a == "sn" && (strlen($val_a) > 60)) {

    echo _("Your last name is longer than 60 characters.");

// Not a valid e-mail
} elseif ($obj_a == "mail" && preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $val_a) == 0) {

    echo _("The e-mail you provided is not valid. Please go back and verify that you have entered it correctly.");
    
} else {

    // VALIDATION PASSED -------------------------------------------------------

    // Processing the strings coming from ajax $_GET
    $who_a = str_replace("__%_%_%__", "=", $who_a);
    $who_a = str_replace("__@_@_@__", ",", $who_a);

    // Building $in array that we pass to ldap_modify
    $in["cn"] = $cn_a;
    $in[$obj_a] = $val_a;

    // Executing the modification of the entry provided
    $mod = ldap_modify($ldapc, $who_a, $in) or die(ldap_error($ldapc));

    // If the modification was a success
    if ($mod) {
        // We log the event
        $log_location = $log_dir . "Ajax.log";
        $log_string = "[" . $time_today . "]: "
                    . _("We have modified the attribute ") . $obj_a
                    . _(" of the user ") . $who_a . ".\n";
        $log_write = file_put_contents($log_location, $log_string, FILE_APPEND | LOCK_EX);
        // We output the modified value so that the form updates through ajax
        echo $val_a;
    }
    
}

// Closing the connection
$ldapx = ldap_close($ldapc)
        or die(
        _("An error has ocurred trying to close the connection to the LDAP database: ")
        . ldap_error($ldapc)
        . "."
    );

?>
