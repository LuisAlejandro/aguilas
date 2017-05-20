<?php

$allowed_ops = array("uid", "mail", "givenName", "sn", "userPassword", "userPasswordBis", "image_captcha");

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
if (!isset($uid) || !isset($givenName) || !isset($sn) || !isset($mail) || !isset($userPassword) || !isset($userPasswordBis) || !isset($description) || !isset($newtoken) || !isset($image_captcha)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $givenName == '' || $sn == '' || $mail == '' || $userPassword == '' || $description == '' || $newtoken == '' || $image_captcha == '') {

    EmptyVariable();

// Invalid username
} elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

    InvalidUsername();
        
// Username has less than 3 characters or more than 30
} elseif ((strlen($uid) < 3) || (strlen($uid) > 30)) {

    WrongUIDLength();

// Invalid e-mail
} elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

    InvalidEMail();

} else {

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit1 = array("uid");
    
    // The filter string to search through LDAP
    $search_string1 = "(uid=".$uid.")";
    
    // The attribute the array of entries is going to be sorted by
    $sort_string1 = 'uid';
    
    // Searching ...
    $search_entries1 = AssistedLDAPSearch($ldapc, $ldap_base, $search_string1, $search_limit1, $sort_string1);
    
    // How much did we get?
    $result_count1 = $search_entries1['count'];

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit2 = array("mail");
    
    // The filter string to search through LDAP
    $search_string2 = "(mail=".$mail.")";
    
    // The attribute the array of entries is going to be sorted by
    $sort_string2 = 'mail';
    
    // Searching ...
    $search_entries2 = AssistedLDAPSearch($ldapc, $ldap_base, $search_string2, $search_limit2, $sort_string2);
    
    // How much did we get?
    $result_count2 = $search_entries2['count'];

    // If we get one or more entries, then the selected username exists
    if ($result_count1 > 0) {

        UserExists();

    // If we get one or more entries, then the e-mail provided is in use
    } elseif ($result_count2 > 0) {

        UsedEMail();

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

    // Invalid Password
    } elseif (preg_match("/^[A-Za-z0-9@#$%^&+=!.-_]+$/", $userPassword) == 0) {

        InvalidPassword();

    // Password has less than 8 characters or more than 30
    } elseif ((strlen($userPassword) < 8) || (strlen($userPassword) > 30)) {

        WrongPasswordLength();

    // Passwords do not match
    } elseif ($userPassword <> $userPasswordBis) {

        DifferentPasswords();

    // Invalid First Name
    } elseif (preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $givenName) == 0) {

        Invalid1Name();

    // First name has more than 60 characters
    } elseif (strlen($givenName) > 60) {

        Wrong1NameLength();

    // Invalid Last Name
    } elseif (preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $sn) == 0) {

        Invalid2Name();

    // Last name has more than 60 characters
    } elseif (strlen($sn) > 60) {

        Wrong2NameLength();

    // Invalid e-mail
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

        InvalidEMail();

    } else {
        
        $val_q = 'DESCRIBE NewUser';
        
        // Let's see if our table exists
        $val_r = AssistedMYSQLQuery($val_q);

        // Create the table if we don't have it
        if (!$val_r) {
            require_once "./libraries/CreateUserTable.inc.php";
        }

        // Encoding the password
        $userPassword = EncodePassword($userPassword, $ldap_enc);
        
        // We build up our query to insert the user data into a temporary MYSQL Database
        // while the user gets the confirmation e-mail and clicks the link
        $ins_q = sprintf("INSERT INTO NewUser "
                ."(uid, givenName, sn, mail, userPassword, description, token) "
                . "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')"
                , mysql_real_escape_string($uid)
                , mysql_real_escape_string($givenName)
                , mysql_real_escape_string($sn)
                , mysql_real_escape_string($mail)
                , mysql_real_escape_string($userPassword)
                , mysql_real_escape_string($description)
                , mysql_real_escape_string($newtoken)
                );

        // Inserting the row on the table ...
        $ins_r = AssistedMYSQLQuery($ins_q);

        // If the insert went OK, we send the notification e-mail to the user
        if ($ins_r) {
            $send = AssistedEMail("NewUserMail", $mail);
        }

        // If the mailing went OK ... 
        if ($send) {
            // We log the event
            WriteLog("NewUserMail");
            // Print the good news to the user
            Success("NewUserMail");
        } else {
            // We fail nicely, at least
            Fail("NewUserMail");
        }            

    }

}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

// Closing the connection
$mysqlx = AssistedMYSQLClose($mysqlc);

require_once "./themes/$app_theme/footer.php";

?>
