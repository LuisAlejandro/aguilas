<?php

$allowed_ops = array("uid", "mail", "image_captcha");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
require_once "./libraries/Functions.inc.php";
require_once "./libraries/Parameters.inc.php";
require_once "./libraries/LDAPConnection.inc.php";
require_once "./libraries/MYSQLConnection.inc.php";


?>

<h2><?= _("Request Status") ?></h2>

<?php

// USER INPUT VALIDATION ------------------------------------------------------- 
// Some of the parameters were not set, the form was not used to get here
if (!isset($uid) || !isset($mail) || !isset($newtoken) || !isset($image_captcha)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $mail == '' || $newtoken == '' || $image_captcha == '') {

    EmptyVariable();
    
// The cookie has expired
} elseif (!isset($session_captcha)) {

    ExpiredCaptcha();

// We compare the cookie hash with the user entry
// If they are different, the user messed up
} elseif ($session_captcha <> $image_captcha) {

    WrongCaptcha();

// Invalid e-mail
} elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

    InvalidEMail();

// Invalid username
} elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

    InvalidUsername();
        
// Username has less than 3 characters or more than 30
} elseif ((strlen($uid) < 3) || (strlen($uid) > 30)) {

    WrongUIDLength();

} else {

    // VALIDATION PASSED ---------------------------------------------------
    // We stablish what attributes are going to be retrieved from each entry
    $search_limit = array("mail", "uid");

    // The filter string to search through LDAP
    $search_string = "(&(mail=".$mail.")(uid=".$uid."))";

    // The attribute the array of entries is going to be sorted by
    $sort_string = 'uid';

    // Searching ...
    $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

    // How much did we get?
    $result_count = $search_entries['count'];

    // If we didn't get any entries, then something is wrong
    if ($result_count == 0) {

        NoResults();

    // If we got more than one entry, then something is really messed up
    } elseif ($result_count > 1) {

        MultipleResults();

    // If we got one coincidence, then we can proceed to modification
    } elseif ($result_count == 1) {

        $val_q = 'DESCRIBE ResetPassword';

        // Let's see if our table exists
        $val_r = AssistedMYSQLQuery($val_q);

        // Create the table if we don't have it
        if (!$val_r) {
            require_once "./libraries/CreatePasswordTable.inc.php";
        }

        // We build up our query to insert the user data into a temporary MYSQL Database
        // while the user gets the confirmation e-mail and clicks the link
        $ins_q = sprintf("INSERT INTO ResetPassword "
                . "(uid, mail, token, description) "
                . "VALUES ('%s', '%s', '%s', '%s')"
                , mysql_real_escape_string($uid)
                , mysql_real_escape_string($mail)
                , mysql_real_escape_string($newtoken)
                , mysql_real_escape_string($description)
                );

        // Inserting the row on the table ...
        $ins_r = AssistedMYSQLQuery($ins_q);

        // If the insert went OK, we send the notification e-mail to the user
        if ($ins_r) {
            $send = AssistedEMail("ResetPasswordMail", $mail);
        }

        // If the mailing went OK ...
        if ($send) {
            // We log the event
            WriteLog("ResetPasswordMail");
            // Print the good news to the user
            Success("ResetPasswordMail");
        } else {
            // We fail nicely, at least
            Fail("ResetPasswordMail");
        }
    }
}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

// Closing the connection
$mysqlx = AssistedMYSQLClose($mysqlc);

require_once "./themes/$app_theme/footer.php";

?>
