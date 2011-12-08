<?php

$ldap_gid_flip = array_flip($ldap_gid);

$ldapc = ldap_connect($ldap_server, $ldap_port)
        or die (
        '<div class="error">'
        . _("##LDAP:CONNECT:ERROR##")
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("##BACK##")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );

$ldapo = ldap_set_option($ldapc, LDAP_OPT_PROTOCOL_VERSION, 3)
        or die (
        '<div class="error">'
        . _("##LDAP:CONFIG:ERROR##")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("##BACK##")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );

$ldapo2 = ldap_set_option($ldapc, LDAP_OPT_SIZELIMIT, 5000)
        or die (
        '<div class="error">'
        . _("##LDAP:CONFIG:ERROR##")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("##BACK##")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );

if ($ldap_tls) {
    $ldapo3 = ldap_start_tls($ldapc)
            or die (
            '<div class="error">'
            . _("##LDAP:TLS:ERROR##")
            . ldap_error($ldapc)
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("##BACK##")
            . '</a></div>'
            . file_get_contents("themes/$app_theme/footer.php")
            );
}

$ldapb = ldap_bind($ldapc,$ldap_dn,$ldap_pass)
        or die (
        '<div class="error">'
        . _("##LDAP:BIND:ERROR##")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("##BACK##")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );

?>