<?php

$allowed_ops = array("uid", "mail", "token");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
require_once "./libraries/Functions.inc.php";
require_once "./libraries/Parameters.inc.php";
require_once "./libraries/LDAPConnection.inc.php";
require_once "./libraries/MYSQLConnection.inc.php";

?>

<h2><?= _("REQUESTSTATUS") ?></h2>

<?php

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
    
// Username has less than 3 characters or more than 30
} elseif ((strlen($uid) < 3) || (strlen($uid) > 30)) {

    WrongUIDLength();

// Invalid e-mail
} elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

    InvalidEMail();

// Invalid Token
} elseif (preg_match("/^[A-Za-z0-9]+$/", $token) == 0) {

    InvalidToken();
    
// Token character length is different than 32
} elseif (strlen($token) <> 32) {

    InvalidToken();
    
} else {
    
    // VALIDATION PASSED -------------------------------------------------------
    // We build up our query to search for the uid and token previously inserted
    // in the temporary database
    $sel_q = sprintf("SELECT * FROM NewUser"
                    . " WHERE mail='%s'"
                    . " AND uid='%s'"
                    . " AND token='%s'"
                    . " ORDER BY token DESC LIMIT 0,1"
                    , mysql_real_escape_string($mail)
                    , mysql_real_escape_string($uid)
                    , mysql_real_escape_string($token)
                    );

    // Searching ...
    $sel_r = AssistedMYSQLQuery($sel_q);

    // How much did we get?
    $sel_n = mysql_num_rows($sel_r);

    // If we get no results, then the user has been created already or
    // there has not been any new user request asociated to the uid or
    // token provided by the user
    if ($sel_n == 0) {

        NoRequests();

    // More than one result is IMPOSSIBLE, didn't we already limit it to 1 on
    // the SQL query?
    } elseif ($sel_n > 1) {

        RuntimeError();
        
    // If there is at least one new user petition, we continue  
    } elseif ($sel_n == 1) {

        while ($row = mysql_fetch_array($sel_r, MYSQL_ASSOC)) {

            // Let's search if the user has already been created
            
            // We stablish what attributes are going to be retrieved from each entry
            $search_limit = array("uid");

            // The filter string to search through LDAP
            $search_string = "(uid=".$uid.")";

            // The attribute the array of entries is going to be sorted by
            $sort_string = 'uid';

            // Searching ...
            $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

            // How much did we get?
            $result_count = $search_entries['count'];
 
            // If we get one result, then the user was created
            // probably the user made multiple new user petitions, created the
            // user and then forgot and clicked the confirmation email again
            if ($result_count == 1) {

                UserExists();
            
            // If we get more than one result, then uid is repeated on the
            // database, which is fatal
            } elseif ($result_count > 1) {
 
                MultipleResults();

            // If there's no results, we are ready to go!
            } elseif ($result_count == 0) {

                // Let's find out what was the last uidNumber created

                // We stablish what attributes are going to be retrieved from each entry
                $search_limit = array("uidNumber");

                // The filter string to search through LDAP
                $search_string = "(uid=maxUID)";

                // The attribute the array of entries is going to be sorted by
                $sort_string = 'uidNumber';

                // Searching ...
                $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

                // How much did we get?
                $result_count = $search_entries['count'];
                
                // If we get no results, then the maxUID entry doesn't exist
                // it means the system has never been used, so we create it
                // and set the last uidNumber to 1
                if ($result_count == 0){
                    
                    // Creating maxUID entry ...
                    require_once "./libraries/CreateMaxUIDEntry.inc.php";
                    
                    // Setting last uidNumber to the first
                    $lastuidnumber = $maxuidstart;
                    
                // If we get more than one maxUID entry, we are in really big
                //  problems: FATAL ERROR TIME
                } elseif($result_count > 1) {
                    
                    MoreThanOneMaxUID();
                    
                // If we got one maxUID entry, we are good to go, the last
                // uidNumber is what we got in the search
                } elseif ($result_count == 1) {
                    $lastuidnumber = $search_entries[0]['uidnumber'][0];
                }

                // We build up an array with all the attributes we want to insert into
                // the new LDAP entry
                $newdn = "uid=" . $row['uid'] . "," . $ldap_base;
                
                // We fill up the entry data on the array
                $in['givenName'] = $row['givenName'];
                $in['sn'] = $row['sn'];
                $in['cn'] = $row['givenName'] . " " . $row['sn'];
                $in['uid'] = $row['uid'];
                $in['mail'] = $row['mail'];
                $mail = $in['mail'];
                $in['uidNumber'] = $lastuidnumber + 1;
                $in['gidNumber'] = $ldap_gid["$ldap_default_group"];
                $in['userPassword'] = $row['userPassword'];
                $in['homeDirectory'] = "/home/" . $row['uid'];
                $in['objectClass'][0] = "inetOrgPerson";
                $in['objectClass'][1] = "posixAccount";
                $in['objectClass'][2] = "top";
                $in['objectClass'][3] = "person";
                $in['objectClass'][4] = "shadowAccount";
                $in['objectClass'][5] = "organizationalPerson";
                $in['loginShell'] = "/bin/false";
                $in['description'] = $row['description'];

                // Adding ...
                $add = AssistedLDAPAdd($ldapc, $newdn, $in);
                
                // If the adding went OK, we increment the maxUID
                if ($add) {
                    
                    // Building the maxUID entry to modify
                    $moddn = "uid=maxUID," . $ldap_base;
                    
                    // We build up the array that's going to modify the maxUID entry
                    $in2['uidNumber'] = $in['uidNumber'];
                    
                    // Incrementing maxUID entry
                    $mod = AssistedLDAPModify($ldapc, $moddn, $in2);
                
                }
                
                // If the modification went OK, we send the notification e-mail to the user
                if ($mod) {
                    $send = AssistedEMail("NewUserDo", $mail);
                }

                // If the mailing went OK ... 
                if ($send) {

                    // We need to get rid of the temporary entry
                    $del_q = sprintf("DELETE FROM NewUser"
                                    . " WHERE uid='%s'"
                                    . " AND token='%s'"
                                    , mysql_real_escape_string($uid)
                                    , mysql_real_escape_string($token)
                                    );

                    // Deleting the row from the table ...
                    $del_r = AssistedMYSQLQuery($del_q);

                    // We log the event
                    WriteLog("NewUserDo");
                    
                    // Print the good news to the user
                    Success("NewUserDo");
                    
                } else {
                    // We fail nicely, at least
                    Fail("NewUserDo");
                }
   
            }

        }
        
    }
    
}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

// Closing the connection
$mysqlx = AssistedMYSQLClose($mysqlc);

require_once "./themes/$app_theme/footer.php";

?>
