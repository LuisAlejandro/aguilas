<?php

$allowed_ops = array("uid", "mail", "token", "givenName", "sn", "userPassword", "userPasswordBis", "image_captcha");

include "config.php";
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "MYSQLConnection.php";
include "Functions.php";

?>

<h2><?= _("Estado de la Solicitud") ?></h2>

<?php

// We store today's date
$time_today = date("d-m-Y-H:i:s");

// Let's see if our table exists
$tbl_test = mysql_query('DESCRIBE NewUser');

// Create the table if we don't have it
if (($tbl_test == FALSE)) {
    include "CreateUserTable.php";
}

// Generate confirmation token to send by e-mail
$token = md5(mt_rand() . "-" . time() . "-" . $_SERVER['REMOTE_ADDR'] . "-" . mt_rand());

// A little description
$description = _("Solicitado por ")
        . $_SERVER['REMOTE_ADDR']
        . _(" a las ")
        . $time_today;

// USER INPUT VALIDATION ------------------------------------------------------- 
// Some of the parameters were not set, the form was not used to get here
if (!isset($uid) || !isset($givenName) || !isset($sn) || !isset($mail) || !isset($userPassword) || !isset($userPasswordBis) || !isset($description) || !isset($token) || !isset($image_captcha)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $givenName == '' || $sn == '' || $mail == '' || $userPassword == '' || $description == '' || $token == '' || $image_captcha == '') {

    EmptyVariable();
} else {

    // CAPTCHA -----------------------------------------------------------------
    // Starting session (cookies)
    session_start();

    // We get the hash from the cookie
    if (isset($_SESSION['captcha'])) {
        $session_captcha = $_SESSION['captcha'];
        // If it's not there, then the cookie expired
    } else {
        $session_captcha = "expired";
    }

    // Let's MD5 the user entry
    $image_captcha = md5($image_captcha);

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit1 = array("uid");
    // The filter string to search through LDAP
    $search_string1 = "(uid=$uid)";
    // The attribute the array of entries is going to be sorted by
    $sort_string1 = 'uid';
    // Searching ...
    $search_entries1 = AssistedLDAPSearch($ldapc, $ldap_base, $search_string1, $search_limit1, $sort_string1);
    // How much did we get?
    $result_count1 = $search_entries1['count'];

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit2 = array("mail");
    // The filter string to search through LDAP
    $search_string2 = "(mail=$mail)";
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
    } elseif ($session_captcha == "expired") {

        ExpiredCaptcha();

    // We compare the cookie hash with the user entry
    // If they are different, the user messed up
    } elseif ($session_captcha <> $image_captcha) {

        WrongCaptcha();

    // Passwords do not match
    } elseif ($userPassword <> $userPasswordBis) {

        DifferentPasswords();

    // Invalid username
    } elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

        InvalidUsername();

    // Invalid Password
    } elseif (preg_match("/^[A-Za-z0-9@#$%^&+=!.-_]+$/", $userPassword) == 0) {

        InvalidPassword();

    // Invalid First Name
    } elseif (preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $givenName) == 0) {
        
        Invalid1Name();
    
    // Invalid Last Name
    } elseif (preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $sn) == 0) {

        Invalid2Name();
        
    // Password has less than 8 characters or more than 20
    } elseif ((strlen($userPassword) < 8) || (strlen($userPassword) > 20)) {

        WrongPasswordLength();
        
    // Invalid e-mail
    } elseif (preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail) == 0) {

        InvalidEMail();
        
    } else {

        // We build up our query to insert the user data into a temporary MYSQL Database
        // while the user gets the confirmation e-mail and clicks the link
        $ins_q = "INSERT INTO NewUser "
                ."(uid, givenName, sn, mail, userPassword, description, token)"
                . " VALUES "
                . "('" . $uid . "', '" . $mail . "', '" . $token . "', '" . $description . "')";

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

include "themes/$app_theme/footer.php";

?>