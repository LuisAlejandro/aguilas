<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die("ERROR");
}

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";

$ldap_gid_flip = array_flip($ldap_gid);

$ldapc = ldap_connect($ldap_server, $ldap_port);

if(!$ldapc){
    echo    '<div class="error">'
            . _("LDAP:CONNECT:ERROR")
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("BACK")
            . '</a></div>';
    include "../themes/$app_theme/footer.php";
    die();
}

$ldapo = ldap_set_option($ldapc, LDAP_OPT_PROTOCOL_VERSION, 3);

if(!$ldapo){
    echo    '<div class="error">'
            . _("LDAP:CONFIG:ERROR")
            . ldap_error($ldapc)
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("BACK")
            . '</a></div>';
    include "../themes/$app_theme/footer.php";
    die();
}

$ldapo2 = ldap_set_option($ldapc, LDAP_OPT_SIZELIMIT, 5000);

if(!$ldapo2){
    echo    '<div class="error">'
            . _("LDAP:CONFIG:ERROR")
            . ldap_error($ldapc)
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("BACK")
            . '</a></div>';
    include "../themes/$app_theme/footer.php";
    die();
}

if ($ldap_tls) {
    $ldapo3 = ldap_start_tls($ldapc);

    if(!$ldapo3){
        echo    '<div class="error">'
                . _("LDAP:TLS:ERROR")
                . ldap_error($ldapc)
                . '.<br /><br /><a href="javascript:history.back(1);">'
                . _("BACK")
                . '</a></div>';
        include "../themes/$app_theme/footer.php";
        die();
    }

}

$ldapb = ldap_bind($ldapc,$ldap_dn,$ldap_pass);

if(!$ldapb){
    echo    '<div class="error">'
            . _("LDAP:BIND:ERROR")
            . ldap_error($ldapc)
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("BACK")
            . '</a></div>';
    include "../themes/$app_theme/footer.php";
    die();
}

?>