<?php

$ldap_gid_flip=array_flip($ldap_gid);

$ldapc = ldap_connect($ldap_server)
        or die (
        '<div class="error">'
        . _("Ocurrió un error en la conexión con el LDAP")
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

$ldapo = ldap_set_option($ldapc, LDAP_OPT_PROTOCOL_VERSION, 3)
        or die (
        '<div class="error">'
        . _("Ocurrió un error durante la configuración del LDAP: ")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

$ldapo2 = ldap_set_option($ldapc, LDAP_OPT_SIZELIMIT, 5000)
        or die (
        '<div class="error">'
        . _("Hubo un error en la configuración del LDAP: ")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

$ldapb = ldap_bind($ldapc,$ldap_dn,$ldap_pass)
        or die (
        '<div class="error">'
        . _("Hubo un error en la asociación con el LDAP: ")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

?>