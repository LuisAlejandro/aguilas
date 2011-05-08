<?php

include "config.php";
$ldapc = ldap_connect($ldap_server) or die ('<div class="error">Hubo un error en la conexión con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$ldapo = ldap_set_option($ldapc, LDAP_OPT_PROTOCOL_VERSION, 3) or die ('<div class="error">Hubo un error en la configuración del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$ldapb = ldap_bind($ldapc,$ldap_dn,$ldap_pass) or die ('<div class="error">Hubo un error en la asociación con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

?>
