<?php

include_once "config.php";
include_once "Locale.php";
include_once "MYSQLConnection.php";
include_once "LDAPConnection.php";

echo _("DROPPING:DATABASE") . $mysql_dbname . " ...\n";
$create_q = 'DROP DATABASE ' . $mysql_dbname;
$create_r = mysql_query($create_q);

echo _("DELETING:LDAP:MAXUID")."\n";
$del = ldap_delete($ldapc, $maxuiddn);

echo _("DONE")."\n";

?>
