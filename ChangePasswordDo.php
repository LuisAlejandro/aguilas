<?php

$allowed_ops = array("uid", "mail", "userPasswordOld", "userPassword", "userPasswordBis", "image_captcha");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
require_once "./libraries/Functions.inc.php";
require_once "./libraries/Parameters.inc.php";
require_once "./libraries/LDAPConnection.inc.php";

?>

<h2><?= _("REQUESTSTATUS") ?></h2>

<?php

// USER INPUT VALIDATION ------------------------------------------------------- 
// Some of the parameters were not set, the form was not used to get here
if (!isset($uid) || !isset($mail) || !isset($userPasswordOld) || !isset($userPassword) || !isset($userPasswordBis) || !isset($image_captcha)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $mail == '' || $userPasswordOld == '' || $userPassword == '' || $userPasswordBis == '' || $image_captcha == '') {

    EmptyVariable();

// The cookie has expired
} elseif (!isset($session_captcha)) {

    ExpiredCaptcha();

// We compare the cookie hash with the user entry
// If they are different, the user messed up
} elseif ($session_captcha <> $image_captcha) {

    WrongCaptcha();

// Invalid username
} elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

    InvalidUsername();

// Username has less than 3 characters or more than 30
} elseif ((strlen($uid) < 3) || (strlen($uid) > 30)) {

    WrongUIDLength();

// Invalid e-mail
} elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

    InvalidEMail();

// Invalid Password
} elseif (preg_match("/^[A-Za-z0-9@#$%^&+=!.-_]+$/", $userPassword) == 0) {

    InvalidPassword();

// Password has less than 8 characters or more than 30
} elseif ((strlen($userPassword) < 8) || (strlen($userPassword) > 30)) {

    WrongPasswordLength();

// Invalid Old Password
} elseif (preg_match("/^[A-Za-z0-9@#$%^&+=!.-_]+$/", $userPasswordOld) == 0) {

    InvalidOldPassword();

// Old Password has less than 8 characters or more than 30
} elseif ((strlen($userPasswordOld) < 8) || (strlen($userPasswordOld) > 30)) {

    WrongOldPasswordLength();
    
// Passwords do not match
} elseif ($userPassword <> $userPasswordBis) {

    DifferentPasswords();

} else {

    // VALIDATION PASSED -------------------------------------------------------

    // Encoding the passwords
    $userPassword = EncodePassword($userPassword, $ldap_enc);
    $userPasswordOld = EncodePassword($userPasswordOld, $ldap_enc);
    
    // We are going to search for a user entry matching the data provided by
    // the user on the form 

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit = array("dn");

    // The filter string to search through LDAP
    $search_string = "(&(mail=" . $mail . ")(userPassword=" . $userPasswordOld . ")(uid=" . $uid . "))";

    // The attribute the array of entries is going to be sorted by
    $sort_string = 'dn';

    // Searching ...
    $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

    // How much did we get?
    $result_count = $search_entries['count'];

    // If we didn't get any entries, there are no user entries matching the 
    // data provided by the user. Maybe the user doesn't exist or the user made
    // a mistake entering the data
    if ($result_count == 0) {

        NoResults();

    // If we got more than one entry, then something is really messed up with
    // the database, there must not be more than one entry with the same data
    } elseif ($result_count > 1) {

        MultipleResults();

    // If we got one coincidence, then we can proceed to modification
    } elseif ($result_count == 1) {

        // What dn are we going to modify?
        $moddn = $search_entries['0']['dn'];

        // We fill in our attribute modificator array
        $in['userPassword'] = $userPassword;

        // Modifying ...
        $mod = AssistedLDAPModify($ldapc, $moddn, $in);

        // If the modifying went OK, we send the notification e-mail to the user
        if ($mod) {
            $send = AssistedEMail("ChangePasswordDo", $mail);
        }

        // If the mailing went OK ... 
        if ($send) {
            // We log the event
            WriteLog("ChangePasswordDo");
            // Print the good news to the user
            Success("ChangePasswordDo");
        } else {
            // We fail nicely, at least
            Fail("ChangePasswordDo");
        }
        
    }
    
}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

require_once "./themes/$app_theme/footer.php";

?>