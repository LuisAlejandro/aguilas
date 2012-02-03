<?php

/******************************************************************************
 *                       DATABASE MANIPULATION FUNCTIONS                      *
 ******************************************************************************/

function AssistedLDAPAdd($ldapc, $newdn, $in) {
    $r_add = ldap_add($ldapc, $newdn, $in)
            or die(
                    '<div class="error">'
                    . _("LDAP:INSERT:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_add);
}
    
function AssistedLDAPModify($ldapc, $moddn, $in) {
    $r_mod = ldap_modify($ldapc, $moddn, $in)
            or die(
                    '<div class="error">'
                    . _("LDAP:MODIFY:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_mod);
}
    
function AssistedLDAPDelete($ldapc, $dn) {
    $r_del = ldap_delete($ldapc, $dn)
            or die(
                    '<div class="error">'
                    . _("LDAP:DELETE:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_del);
}

function AssistedLDAPClose($ldapc) {
    $ldapx = ldap_close($ldapc)
            or die(
                    '<div class="error">'
                    . _("LDAP:CLOSE:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($ldapx);
}

function AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string) {

    // Searching...
    $search_result = ldap_search($ldapc, $ldap_base, $search_string, $search_limit)
            or die(
                    '<div class="error">'
                    . _("LDAP:SEARCH:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );

    // Sorting the result by cn
    $search_sort = ldap_sort($ldapc, $search_result, $sort_string)
            or die(
                    '<div class="error">'
                    . _("LDAP:SORT:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );

    // Getting the all the entries
    $search_entries = ldap_get_entries($ldapc, $search_result)
            or die(
                    '<div class="error">'
                    . _("LDAP:GET:ERROR")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($search_entries);
}

function AssistedMYSQLQuery($query) {
    $result = mysql_query($query)
            or die(
                    '<div class="error">'
                    . _("MYSQL:QUERY:ERROR")
                    . mysql_error($query)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($result);
}

function AssistedMYSQLClose($mysqlc) {
    $mysqlx = mysql_close($mysqlc)
            or die(
                    '<div class="error">'
                    . _("MYSQL:CLOSE:ERROR")
                    . mysql_error($mysqlc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("BACK")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($mysqlx);
}


/******************************************************************************
 *                           COMMUNICATION FUNCTIONS                          *
 ******************************************************************************/

function AssistedEMail($what, $where) {
    // What are the headers?
    $headers = "From: " . $app_mail . "\nContent-Type: text/html; charset=utf-8";

    // What's the message?
    switch ($what) {
        case "ChangePasswordDo":
            $subject = _("EMAIL:PASSWORD:CHANGE") . $app_name;
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
                    . _("EMAIL:PASSWORD:GREETINGS") . $app_name . _("EMAIL:PASSWORD:HASBEENCHANGED")
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordMail":
            $subject = _("EMAIL:PASSWORD:NEW") . $app_name;
            $go_link = "http://" . $app_url . "/ResetPasswordDo.php"
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
                    . '<p>'
                    . _("EMAIL:PASSWORD:WHY")
                    . '<strong>' . $uid . '</strong>'
                    . _("IN") . $app_name . '.'
                    . '</p><p>'
                    . _("EMAIL:CLICK:CONFIRM")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">CONFIRMAR</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordDo":
            $subject = _("EMAIL:PASSWORD:NEW2") . $app_name;
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
                    . _("EMAIL:PASSWORD:GREETINGS2")
                    . '</p>'
                    . '<p><strong>' . $genPassword . '</strong></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "NewUserMail":
        case "ResendMailDo":
            $subject = _("EMAIL:NEWUSER:ACTIVATION") . $app_name;
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
                    . '<p>' . _("HI") . '<strong>' . $givenName . '</strong>.</p>'
                    . '<p>'
                    . _("EMAIL:NEWUSER:REQUEST")
                    . $app_name . '.'
                    . '</p><p>'
                    . _("EMAIL:CLICK:CONFIRM")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">CONFIRMAR</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "DeleteUserDo":
            $subject = _("EMAIL:DELETEDUSER:TITLE") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("EMAIL:DELETEDUSER:GREETINGS") . '"<strong>' . $uid . '</strong>".</p>'
                    . '<p>'
                    . _("EMAIL:DELETEDUSER:SUCCESS")
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

function WriteLog($data, $log_file, $time_today) {
    $log_location = "/var/log/aguilas/" . $log_file . ".log";
    switch ($log_file) {
        case "ChangePasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:CHANGEPASSWORD:DONE")
                    . $data['mail'] . " (uid: " . $data['uid'] . ").\n";
            break;
        case "ResetPasswordMail":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:RESETPASSWORD:CONFIRM")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "ResetPasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:RESETPASSWORD:DONE")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "ResendMailDo":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:NEWUSER:CONFIRM:AGAIN")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "NewUserMail":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:NEWUSER:CONFIRM")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "DeleteUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:DELETEUSER:DONE")
                    . $data['mail']
                    . " (uid: " . $data['uid'] . ").\n";
            break;
        case "NewUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("LOG:NEWUSER:DONE")
                    . $data['mail']
                    . " (uid: " . $data['uid'] . ").\n";
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
        case "PLAIN":
            $hash = $password;
            break;
        case "SSHA":
            for ($i = 1; $i <= 10; $i++) {
                $salt .= substr('0123456789abcdef', rand(0, 15), 1);
            }
            $hash = "{SSHA}" . base64_encode(pack("H*", sha1($password . $salt)) . $salt);
            break;
        case "MD5":
            $hash = "{MD5}" . base64_encode(pack("H*", md5($password)));
            break;
    }
    return $hash;
}

function InitCaptcha() {
    // CAPTCHA -----------------------------------------------------------------
    // Starting session (cookies)
    session_start();
    // We get the hash from the cookie
    // If it's not there, then the cookie expired
    if (isset($_SESSION['captcha'])) {
        $session_captcha = $_SESSION['captcha'];
    }

    if (isset($image_captcha)){
        // Let's MD5 the user entry
        $image_captcha = md5($image_captcha);
    } else {
        WrongCaptcha();
    }
}

function VariableNotSet() {
    ?>
    <div class="error">
        <?= _("FORM:ERROR") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function EmptyVariable() {
    ?>
    <div class="error">
        <?= _("FORM:ERROR") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidSearch() {
    ?>
    <div class="error">
        <?= _("SEARCH:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidEMail() {
    ?>
    <div class="error">
        <?= _("EMAIL:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidToken() {
    ?>
    <div class="error">
        <?= _("TOKEN:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidUsername() {
    ?>
    <div class="error">
        <?= _("USERNAME:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidPassword() {
    ?>
    <div class="error">
        <?= _("PASSWORD:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function InvalidOldPassword() {
    ?>
    <div class="error">
        <?= _("OLDPASSWORD:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function DifferentPasswords() {
    ?>
    <div class="error">
        <?= _("PASSWORD:DIFFERENT") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function WrongPasswordLength() {
    ?>
    <div class="error">
        <?= _("PASSWORD:INVALID:LENGTH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function WrongUIDLength() {
    ?>
    <div class="error">
        <?= _("USERNAME:INVALID:LENGTH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function Wrong1NameLength() {
    ?>
    <div class="error">
        <?= _("FIRSTNAME:INVALID:LENGTH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function Wrong2NameLength() {
    ?>
    <div class="error">
        <?= _("LASTNAME:INVALID:LENGTH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function WrongOldPasswordLength() {
    ?>
    <div class="error">
        <?= _("OLDPASSWORD:INVALID:LENGTH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function Invalid1Name() {
    ?>
    <div class="error">
        <?= _("FIRSTNAME:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function Invalid2Name() {
    ?>
    <div class="error">
        <?= _("LASTNAME:INVALID") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function UserExists() {
    ?>
    <div class="error">
        <?= _("USERNAME:EXISTS") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function UsedEMail() {
    ?>	
    <div class="error">
        <?= _("EMAIL:EXISTS") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

/******************************************************************************
 *                        RESULT MESSAGES FUNCTIONS                           *
 ******************************************************************************/

function NoRequests() {
    ?>
    <div class="error">
        <?= _("NOMATCH") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function MultipleResults() {
    ?>
    <div class="error">
        <?= _("DATABASE:CORRUPTION") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function NoResults() {
?>
    <div class="error">
        <?= _("NOMATCH:ACCOUNT") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
<?php
}

function Fail($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $fail_string = _("FINAL:ERROR:CHANGEPASSWORD");
            break;
        case "ResetPasswordMail":
            $fail_string = _("FINAL:ERROR:CONFIRMATION");
            break;
        case "ResetPasswordDo":
            $fail_string = _("FINAL:ERROR:RESETPASSWORD");
            break;
        case "DeleteUserDo":
            $fail_string = _("FINAL:ERROR:DELETEUSER");
            break;
        case "NewUserDo":
            $fail_string = _("FINAL:ERROR:NEWUSER");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $fail_string = _("FINAL:ERROR:CONFIRMATION");
            break;
    }
    ?>
    <div class="error">
        <?= $fail_string ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("BACK") ?></a>
    </div>
    <?php
}

function Success($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $success_string = _("FINAL:SUCCESS:RESETPASSWORD");
            break;
        case "ResetPasswordMail":
            $success_string = _("FINAL:CONFIRM:RESETPASSWORD");
            break;
        case "ResetPasswordDo":
            $success_string = _("FINAL:SUCCESS:RESETPASSWORD:EMAIL");
            break;
        case "DeleteUserDo":
            $success_string = _("FINAL:SUCCESS:DELETE");
            break;
        case "NewUserDo":
            $success_string = _("FINAL:SUCCESS:NEWUSER");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $success_string = _("FINAL:CONFIRM:NEWUSER");
            break;
    }
    ?>
    <div class="exito">
        <?= $success_string ?>
        <br /><br />
        <a href="index.php"><?= _("START") ?></a>
    </div>
    <?php
}

// HTML writer Library
    function ParseUserTable($search_entries, $result_count) {
        $result_count_1 = $result_count - 1;
        ?>
    <table>
        <tr>
            <td class="px70">
                <strong><?= _("ID") ?></strong>
            </td>
            <td class="px300">
                <strong><?= _("USERNAME") ?></strong>
            </td>
            <td class="px360">
                <strong><?= _("REALNAME") ?></strong>
            </td>
            <td class="px70">
                <strong><?= _("GROUP") ?></strong>
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
                echo _("CLICKTOEDIT");
            } else {
                echo _("CANNOTEDIT");
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
                                    <input class="AjaxButton" onclick="sendAjax('<?= $objects ?>','<?= $who ?>');" type="button" value="<?= _("SAVE") ?>" />&nbsp;
                                    <input class="AjaxButton" onclick="cancelAjax('<?= $objects ?>');" type="button" value="<?= _("CANCEL") ?>" />
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
