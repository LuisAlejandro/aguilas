<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die("ERROR");
}

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";

/******************************************************************************
 *                       DATABASE MANIPULATION FUNCTIONS                      *
 ******************************************************************************/

function AssistedLDAPAdd($ldapc, $newdn, $in) {
    // Use these variables that are outside the function
    global $app_theme;
    // Add the new entry
    $r_add = ldap_add($ldapc, $newdn, $in);
    // Let's see if you could make it
    if(!$r_add){
        echo    '<div class="error">'
                . _("An error has ocurred trying to insert entries on the LDAP database: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($r_add);
}
    
function AssistedLDAPModify($ldapc, $moddn, $in) {
    // Use these variables that are outside the function
    global $app_theme;
    // Modify the entry
    $r_mod = ldap_modify($ldapc, $moddn, $in);
    // Let's see if you could make it
    if(!$r_mod){
        echo    '<div class="error">'
                . _("An error has ocurred trying to modify entries on the LDAP database: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($r_mod);
}
    
function AssistedLDAPDelete($ldapc, $dn) {
    // Use these variables that are outside the function
    global $app_theme;
    // Delete the entry
    $r_del = ldap_delete($ldapc, $dn);
    // Let's see if you could make it
    if(!$r_del){
        echo    '<div class="error">'
                . _("An error has ocurred trying to delete entries on the LDAP database: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($r_del);
}

function AssistedLDAPClose($ldapc) {
    // Use these variables that are outside the function
    global $app_theme;
    // Close the connection
    $ldapx = ldap_close($ldapc);
    // Let's see if you could make it
    if(!$ldapx){
        echo    '<div class="error">'
                . _("An error has ocurred trying to close the connection to the LDAP database: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($ldapx);
}

function AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string) {

    // Searching...
    $search_result = ldap_search($ldapc, $ldap_base, $search_string, $search_limit);
    // Let's see if you could make it
    if(!$search_result){
        echo    '<div class="error">'
                . _("An error has ocurred while the system was performing a search: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }

    // Sorting the result by cn
    $search_sort = ldap_sort($ldapc, $search_result, $sort_string);
    // Let's see if you could make it
    if(!$search_sort){
        echo    '<div class="error">'
                . _("There was an error organizing the LDAP search results: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }

    // Getting the all the entries
    $search_entries = ldap_get_entries($ldapc, $search_result);
    // Let's see if you could make it
    if(!$search_entries){
        echo    '<div class="error">'
                . _("There was an error retrieving the LDAP search results: ")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($search_entries);
}

function AssistedMYSQLQuery($query) {
    // Use these variables that are outside the function
    global $app_theme;
    // Perform the query
    $result = mysql_query($query);
    // Let's see if you could make it
    if(!$result){
        echo    '<div class="error">'
                . _("An error has ocurred while the system was performing a MYSQL query on the database: ")
                . mysql_error($mysqlc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($result);
}

function AssistedMYSQLClose($mysqlc) {
    // Use these variables that are outside the function
    global $app_theme;
    // Close the connection
    $mysqlx = mysql_close($mysqlc);
    // Let's see if you could make it
    if(!$mysqlx){
        echo    '<div class="error">'
                . _("An error has ocurred trying to close the connection to the MYSQL database: ")
                . mysql_error($mysqlc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("Back")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }
    return($mysqlx);
}


/******************************************************************************
 *                           COMMUNICATION FUNCTIONS                          *
 ******************************************************************************/

function AssistedEMail($what, $where) {
    global $app_mail, $app_name, $app_locale, $app_operator, $mail, $uid, $token, $newtoken, $app_url, $genPassword, $givenName;
    // What are the headers?
    $headers = "From: " . $app_mail . "\nContent-Type: text/html; charset=utf-8";

    // What's the message?
    switch ($what) {
        case "ChangePasswordDo":
            $subject = _("Password Change in ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("Hi! Your password in ") . $app_name . " " . _("has been changed.")
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordMail":
            $subject = _("New Password petition in ") . $app_name;
            $go_link = "http://" . $app_url . "/ResetPasswordDo.php"
                    . "?mail=" . $mail
                    . "&uid=" . $uid
                    . "&token=" . $newtoken;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("You have received this e-mail because someone made a new password petition for the user ")
                    . '<strong>' . $uid . '</strong>'
                    . _(" in ") . $app_name . '.'
                    . '</p><p>'
                    . _("Click the following link to confirm your petition.")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">' . _("CONFIRM") . '</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordDo":
            $subject = _("New Password in ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("Hi, your new password is: ")
                    . '</p>'
                    . '<p><strong>' . $genPassword . '</strong></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "NewUserDo":
            $subject = _("New User in ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Hi, ") . '<strong>' . $uid . '</strong>.</p>'
                    . '<p>'
                    . _("Your account has been successfully created")
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;
        
        case "NewUserMail":
            $subject = _("New user activation in ") . $app_name;
            $go_link = "http://" . $app_url . "/NewUserDo.php"
                    . "?mail=" . $mail
                    . "&uid=" . $uid
                    . "&token=" . $newtoken;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Hi, ") . '<strong>' . $givenName . '</strong>.</p>'
                    . '<p>'
                    . _("You have received this e-mail because you made a new user request in ")
                    . $app_name . '.'
                    . '</p><p>'
                    . _("Click the following link to confirm your petition.")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">' . _("CONFIRM") . '</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResendMailDo":
            $subject = _("New user activation in ") . $app_name;
            $go_link = "http://" . $app_url . "/NewUserDo.php"
                    . "?mail=" . $mail
                    . "&uid=" . $uid
                    . "&token=" . $token;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Hi, ") . '<strong>' . $givenName . '</strong>.</p>'
                    . '<p>'
                    . _("You have received this e-mail because you made a new user request in ")
                    . $app_name . '.'
                    . '</p><p>'
                    . _("Click the following link to confirm your petition.")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">' . _("CONFIRM") . '</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "DeleteUserDo":
            $subject = _("Deleted User in ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Dear user ") . '"<strong>' . $uid . '</strong>".</p>'
                    . '<p>'
                    . _("Your account has been successfully deleted from ")
                    . $app_name . '.'
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;
    }
    $send = mail($where, $subject, $body, $headers);
    return ($send);
}

/******************************************************************************
 *                           EVENT LOGGING FUNCTIONS                          *
 ******************************************************************************/

function WriteLog($log_file) {
    global $uid, $mail, $token, $time_today;
    $log_location = $log_dir . $log_file . ".log";
    switch ($log_file) {
        case "ChangePasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("[ Change Password ] A success e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . ").\n";
            break;
        case "ResetPasswordMail":
            $log_string = "[" . $time_today . "]: "
                    . _("[ Reset Password ] A confirmation e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . "; token: " . $token . ").\n";
            break;
        case "ResetPasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("[ Reset Password ] A success e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . "; token: " . $token . ").\n";
            break;
        case "ResendMailDo":
            $log_string = "[" . $time_today . "]: "
                    . _("[ New User ] A confirmation e-mail has been re-sent to ")
                    . $mail . " (uid: " . $uid . "; token: " . $token . ").\n";
            break;
        case "NewUserMail":
            $log_string = "[" . $time_today . "]: "
                    . _("[ New User ] A confirmation e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . "; token: " . $token . ").\n";
            break;
        case "DeleteUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("[ Delete User ] A success e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . ").\n";
            break;
        case "NewUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("[ New User ] A success e-mail has been sent to ")
                    . $mail . " (uid: " . $uid . ").\n";
            break;
    }
    $log_write = file_put_contents($log_location, $log_string, FILE_APPEND | LOCK_EX);
    return($log_write);
}

/******************************************************************************
 *                           VALIDATION FUNCTIONS                             *
 ******************************************************************************/

function EncodePassword($password, $type) {
    switch ($type) {
        case "CLEAR":
            $hash = $password;
            break;
        
        case "CRYPT":
            $hash = "{CRYPT}" . crypt($password);
            break;
        
        case "SHA":
            $hash = "{SHA}" . base64_encode(pack("H*", sha1($password)));
            break;
            
        case "MD5":
            $hash = "{MD5}" . base64_encode(pack("H*", md5($password)));
            break;
    }
    return $hash;
}

function ExpiredCaptcha() {
    ?>
    <div class="error">
        <?= _("The page has expired, please go back and try again.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function WrongCaptcha() {
    ?>
    <div class="error">
        <?= _("You have entered the verification code (captcha) incorrectly, please go back and try again.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function VariableNotSet() {
    ?>
    <div class="error">
        <?= _("There was an error processing the form. Please go back and verify that you have entered the information correctly.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function EmptyVariable() {
    ?>
    <div class="error">
        <?= _("There was an error processing the form. Please go back and verify that you have entered the information correctly.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidSearch() {
    ?>
    <div class="error">
        <?= _("Your search terms have invalid characters. You can only use letters (uppercase, lowercase), accented letters, umlaut and underscore.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidEMail() {
    ?>
    <div class="error">
        <?= _("The e-mail you provided is not valid. Please go back and verify that you have entered it correctly.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidToken() {
    ?>
    <div class="error">
        <?= _("The confirmation token is invalid. Please make the request again.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidUsername() {
    ?>
    <div class="error">
        <?= _("The username is invalid. You can only use letters (uppercase, lowercase), numbers, dashes and underscores.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidPassword() {
    ?>
    <div class="error">
        <?= _("The password has invalid characters. You can only use letters (uppercase, lowercase), numbers and these symbols: . ! @ # $ % ^ + = - _") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function InvalidOldPassword() {
    ?>
    <div class="error">
        <?= _("The old password has invalid characters, which is very strange. Please use the Reset Password form to change your password, and then come back here.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function DifferentPasswords() {
    ?>
    <div class="error">
        <?= _("Passwords do not match.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function WrongPasswordLength() {
    ?>
    <div class="error">
        <?= _("Password length must be shorter than 30 characters and longer than 8.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function WrongUIDLength() {
    ?>
    <div class="error">
        <?= _("Username length must be shorter than 30 characters and longer than 3.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function Wrong1NameLength() {
    ?>
    <div class="error">
        <?= _("Your first name is longer than 60 characters.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function Wrong2NameLength() {
    ?>
    <div class="error">
        <?= _("Your last name is longer than 60 characters.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function WrongOldPasswordLength() {
    ?>
    <div class="error">
        <?= _("Your old password length is too short or too long, which is very strange. Please use the Reset Password form to change your password, and then come back here.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function Invalid1Name() {
    ?>
    <div class="error">
        <?= _("Your first name has invalid characters. You can only use letters (uppercase, lowercase), accented letters or with umlaut.") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function Invalid2Name() {
    ?>
    <div class="error">
        <?= _("Your last name has invalid characters. You can only use letters (uppercase, lowercase), accented letters or with umlaut.") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function UserExists() {
    ?>
    <div class="error">
        <?= _("The username already exists.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function UsedEMail() {
    ?>	
    <div class="error">
        <?= _("The e-mail you provided is already asociated to an existing account.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

/******************************************************************************
 *                        RESULT MESSAGES FUNCTIONS                           *
 ******************************************************************************/

function NoRequests() {
    ?>
    <div class="error">
        <?= _("There are no petitions matching the data provided.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function MultipleResults() {
    ?>
    <div class="error">
        <?= _("There is an inconsistency with the database. There are two or more accounts sharing some user data (username, e-mail, etc...), which generates errors and inaccuracies on the system. This error has been informed to system administrators.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function NoResults() {
?>
    <div class="error">
        <?= _("The data provided doesn't match any existing account. Please go back and check the information you gave us.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
<?php
}

function Fail($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $fail_string = _("again later.");
            break;
        case "ResetPasswordMail":
            $fail_string = _("There was an error sending the confirmation e-mail. Please try again later.");
            break;
        case "ResetPasswordDo":
            $fail_string = _("There was an error generating your new password. Please try again later.");
            break;
        case "DeleteUserDo":
            $fail_string = _("There was an error deleting your account. Please try again later.");
            break;
        case "NewUserDo":
            $fail_string = _("There was an error creating your account. Please try again later.");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $fail_string = _("There was an error sending the confirmation e-mail. Please try again later.");
            break;
    }
    ?>
    <div class="error">
        <?= $fail_string ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Back") ?></a>
    </div>
    <?php
}

function Success($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $success_string = _("Success! Your password has been changed.");
            break;
        case "ResetPasswordMail":
            $success_string = _("Your new password request has been correctly processed. Please check your e-mail to finish the process.");
            break;
        case "ResetPasswordDo":
            $success_string = _("Success! We have generated a new password for you. Please check your e-mail to find it.");
            break;
        case "DeleteUserDo":
            $success_string = _("Your account has been successfully deleted.");
            break;
        case "NewUserDo":
            $success_string = _("Success! Your account has been successfully created.");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $success_string = _("The new user account has been processed correctly. Please check your e-mail to finish the process.");
            break;
    }
    ?>
    <div class="exito">
        <?= $success_string ?>
        <br /><br />
        <a href="index.php"><?= _("Start") ?></a>
    </div>
    <?php
}

// HTML writer Library
    function ParseUserTable($search_entries, $result_count) {
        global $ldap_gid_flip;
        $result_count_1 = $result_count - 1;
        ?>
    <table>
        <tr>
            <td class="px70">
                <strong><?= _("ID") ?></strong>
            </td>
            <td class="px300">
                <strong><?= _("Username") ?></strong>
            </td>
            <td class="px360">
                <strong><?= _("Real Name") ?></strong>
            </td>
            <td class="px70">
                <strong><?= _("Group") ?></strong>
            </td>
        </tr>
    <?php
// Parsing the results nice and neat
    for ($i = 0; $i <= $result_count_1; $i++) {
        echo '<tr><td class="px70">' . $search_entries[$i]['uidnumber'][0] . '</td>';
        echo '<td class="px300">' . $search_entries[$i]['uid'][0] . '</td>';
        echo '<td class="px360">' . $search_entries[$i]['cn'][0] . '</td>';
        echo '<td class="px70">' . $ldap_gid_flip[$search_entries[$i]['gidnumber'][0]] . '</td></tr>';
    }
    ?>
    </table>
        <?php
    }

function AJAXAssistant($objects, $tags, $contents, $edit, $who) {
        ?>
    <tr>
        <td class="px160">
            <?= $tags ?>
        </td>
        <td class="px120">
            <?php
            
            if ($edit) {
                echo _("click the field to edit");
            } else {
                echo _("this field cannot be edited");
            }
            
            ?>
        </td>
        <td class="px640">
            <div>
                <?php

                if ($edit) {
                    echo '<table class="infoBox" cellSpacing="2" cellPadding="3">';
                } else {
                    echo '<table class="infoBox_null">';
                }

                ?>
                    <tr valign="middle">
                        <td id="<?= $objects ?>_rg" <?php if ($edit) { ?>onmouseover="flashRow(this);" onclick="changeAjax('<?= $objects ?>');" onmouseout="unFlashRow(this);"<?php } ?>>
                            <div class="superBigSize" id="<?= $objects ?>_rg_display_section">
                                <?= $contents ?>
                            </div>
                        </td>
                            <?php if ($edit) { ?>
                            <td id="<?= $objects ?>_hv">
                                <div id="<?= $objects ?>_hv_editing_section">
                                    <input class="superBigSize editMode" id="<?= $objects ?>" name="<?= $objects ?>" value="<?= $contents ?>" <?php if ($objects == "givenName" || $objects == "sn") { ?>onkeyup="update_cn();"<?php } ?> />&nbsp;
                                    <input class="AjaxButton" onclick="sendAjax('<?= $objects ?>','<?= $who ?>');" type="button" value="<?= _("Save") ?>" />&nbsp;
                                    <input class="AjaxButton" onclick="cancelAjax('<?= $objects ?>');" type="button" value="<?= _("Cancel") ?>" />
                                </div>
                                <span class="savingAjaxWithBackground" id="<?= $objects ?>_hv_saving_section">
                                    &#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;
                                </span>
                                <script type="text/javascript">
                                    document.getElementById('<?= $objects ?>_hv').style.display = 'none';
                                    document.getElementById('<?= $objects ?>_hv_saving_section').style.display = 'none';
                                </script>
                            </td>
    <?php } ?>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <?php
}



?>
