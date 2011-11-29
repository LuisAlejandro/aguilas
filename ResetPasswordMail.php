<?php

$allowed_ops = array("uid", "mail", "image_captcha");

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
$tbl_test = mysql_query('DESCRIBE ResetPassword');

// Create the table if we don't have it
if (($tbl_test == FALSE)) {
    include "CreatePasswordTable.php";
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
if (!isset($uid) || !isset($mail) || !isset($token) || !isset($image_captcha)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $mail == '' || $token == '' || $image_captcha == '') {

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

    // The cookie has expired
    if ($session_captcha == "expired") {

        ExpiredCaptcha();

        // We compare the cookie hash with the user entry
        // If they are different, the user messed up
    } elseif ($session_captcha <> $image_captcha) {

        WrongCaptcha();

        // Invalid e-mail
    } elseif (preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail) == 0) {

        InvalidEMail();

        // Invalid username
    } elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

        InvalidUsername();
    } else {

        // VALIDATION PASSED ---------------------------------------------------
        // We stablish what attributes are going to be retrieved from each entry
        $search_limit = array("mail", "uid");

        // The filter string to search through LDAP
        $search_string = "(&(mail=$mail)(uid=$uid))";

        // The attribute the array of entries is going to be sorted by
        $sort_string = 'cn';

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

            // We build up our query to insert the user data into a temporary MYSQL Database
            // while the user gets the confirmation e-mail and clicks the link
            $ins_q = "INSERT INTO ResetPassword (uid, mail, token, description) VALUES ('"
                    . $uid . "', '"
                    . $mail . "', '"
                    . $token . "', '"
                    . $description . "')";

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
}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

// Closing the connection
$mysqlx = AssistedMYSQLClose($mysqlc);

include "themes/$app_theme/footer.php";

?>