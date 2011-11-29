<?php

$allowed_ops = array("uid", "mail", "token");

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

// USER INPUT VALIDATION ------------------------------------------------------- 
// Some of the parameters were not set, the form was not used to get here
if (!isset($uid) || !isset($mail) || !isset($token)) {

    VariableNotSet();

// Some of the parameters are empty
} elseif ($uid == '' || $mail == '' || $token == '') {

    EmptyVariable();

// Invalid username
} elseif (preg_match("/^[A-Za-z0-9_-]+$/", $uid) == 0) {

    InvalidUsername();

// Invalid e-mail
} elseif (preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail) == 0) {

    InvalidEMail();

// Invalid Token
} elseif (preg_match("/^[A-Za-z0-9]+$/", $token) == 0) {

    InvalidToken();

} else {

    // VALIDATION PASSED -------------------------------------------------------
    // Let's search in the temporary database for the mail, user and token that we
    // are getting from the e-mail we sent to the user
    $sel_q = "SELECT * FROM ResetPassword"
                    . " WHERE mail='" . $mail . "'"
                    . " AND uid='" . $uid . "'"
                    . " AND token='" . $token . "'"
                    . " ORDER BY token DESC LIMIT 0,1";

    // Searching ...
    $sel_r = AssistedMYSQLQuery($sel_q);

    // How much did we get?
    $sel_n = mysql_num_rows($sel_r);

    // If nothing found, we got an invalid token, email or uid
    if ($sel_n == 0) {

        InvalidToken();

    // If more than one found, we got a database corruption
    // tell the user he got an invalid token and ask him to repeat the operation
    } elseif ($sel_n > 1) {

        InvalidToken();

    // If we got one coincidence, everything is going OK
    } elseif ($sel_n == 1) {

        // Navigate through results
        while ($row = mysql_fetch_array($sel_r, MYSQL_ASSOC)) {

            // Let's update our info with fresh data from the database
            $mail = $row['mail'];
            $uid = $row['uid'];

            // Generating pretty badass password
            $in['userPassword'] = substr(md5(md5(mt_rand() . "-" . $mail . "+" . $uid . "+" . time() . "-" . mt_rand())), 0, 8);
            $newPassword = $in['userPassword'];

            // We stablish what attributes are going to be retrieved from each entry
            $search_limit = array("dn");

            // The filter string to search through LDAP
            $search_string = "(&(mail=$mail)(uid=$uid))";

            // The attribute the array of entries is going to be sorted by
            $sort_string = 'cn';

            // Searching ...
            $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

            // How much did we get?
            $result_count = $search_entries['count'];

            // If we have zero coincidences, then the user somehow was removed from LDAP
            // just after he made the Reset Password petition
            if ($result_count == 0) {

                NoResults();

            // If we have more than one entry with the same data, then database is corrupted
            // and we are in a big mess
            } elseif ($result_count > 1) {

                MultipleResults();

            // If we got one coincidence, we are on the right path
            } elseif ($result_count == 1) {

                // Modifying ...
                $mod = AssistedLDAPModify($ldapc, $search_entries['0']['dn'], $in);

                // If the modifying went OK, we send the notification e-mail to the user
                if ($mod) {
                    $send = AssistedEMail("ResetPasswordDo", $mail);
                }

                // If the mailing went OK ... 
                if ($send) {

                    // We need to get rid of the temporary entry
                    $del_q = "DELETE FROM ResetPassword"
                                    . " WHERE mail='" . $mail . "'"
                                    . " AND uid='" . $uid . "'"
                                    . " AND token='" . $token . "'";

                    // Deleting the row from the table ...
                    $del_r = AssistedMYSQLQuery($del_q);

                    // We log the event
                    WriteLog("ResetPasswordDo");
                    // Print the good news to the user
                    Success("ResetPasswordDo");
                } else {
                    // We fail nicely, at least
                    Fail("ResetPasswordDo");
                }
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