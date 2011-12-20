<?php

$allowed_ops = array("uid", "userPassword", "image_captcha");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
require_once "./libraries/Functions.inc.php";
require_once "./libraries/Parameters.inc.php";
require_once "./libraries/LDAPConnection.inc.php";

InitCaptcha();

?>

<h2><?= _("USERPROFILE") ?></h2>

<?php

// USER INPUT VALIDATION ------------------------------------------------------- 
// Some of the parameters were not set, the form was not used to get here
if (!isset($uid) || !isset($userPassword) || !isset($image_captcha)) {

    VariableNotSet();
  
// Some of the parameters are empty
} elseif ($uid == '' || $userPassword == '' || $image_captcha == '') {

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

// Invalid Password
} elseif (preg_match("/^[A-Za-z0-9@#$%^&+=!.-_]+$/", $userPassword) == 0) {

    InvalidPassword();
    
// Password has less than 8 characters or more than 30
} elseif ((strlen($userPassword) < 8) || (strlen($userPassword) > 30)) {

    WrongPasswordLength();

} else {

    // VALIDATION PASSED -------------------------------------------------------

    // We are going to search for a user matching the data entered 

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit = array("dn", "givenName", "sn", "cn", "uid", "userPassword", "mail", "uidNumber", "gidNumber");

    // The filter string to search through LDAP
    $search_string = "(&(userPassword=" . $userPassword . ")(uid=" . $uid . "))";

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

    // If we got one coincidence, then we can proceed to deletion
    } elseif ($result_count == 1) {

        // Constructing an array of the name of theelements we want to edit ...
        $objects = array("uid", "uidNumber", "givenName", "sn", "cn", "mail", "userPassword", "gidNumber");
        
        // ... and it's descriptive tags
        $tags = array(  _("USERNAME"),
                        _("ID"),
                        _("FIRSTNAME:FORM"),
                        _("LASTNAME:FORM"),
                        _("COMPLETENAME:FORM"),
                        _("EMAIL"),
                        _("PASSWORD"),
                        _("GROUP"));
        
        // We get their respective values in an array too
        $contents = array(  $search_entries[0]['uid'][0],
                            $search_entries[0]['uidnumber'][0],
                            $search_entries[0]['givenname'][0],
                            $search_entries[0]['sn'][0],
                            $search_entries[0]['cn'][0],
                            $search_entries[0]['mail'][0],
                            preg_replace("/[A-Za-z0-9@#$%^&+=!.-_]/", "*", $search_entries[0]['userpassword'][0]),
                            $ldap_gid_flip[$search_entries[0]['gidnumber'][0]]);
        
        // Tell me which ones you want to make editable and which don't
        // 1 = editable
        // 0 = uneditable
        $edit = array(0, 0, 1, 1, 0, 1, 0, 0);
        
        // How much do we have?
        $num = count($edit) - 1;
        
        // Let's filter some ajax-incompatible characters first
        $who = str_replace(",", "__@_@_@__", $search_entries[0]['dn']);
        $who = str_replace("=", "__%_%_%__", $who);

        // Finally, we build up all the AJAX form with our assistant
        echo "<table>";
        for ($i = 0; $i <= $num; $i++) {
            AJAXAssistant($objects[$i], $tags[$i], $contents[$i], $edit[$i], $who);
        }
        echo "</table>";
    }
    
}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

require_once "./themes/$app_theme/footer.php";

?>