<?php

$allowed_ops = "uninstall";

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";

// Prevent to be loaded from the webserver
if (array_key_exists('REMOTE_ADDR', $_SERVER)&&!isset($_SERVER['argc'])) {
    die("ERROR");
}

require_once "./libraries/MYSQLConnection.inc.php";
require_once "./libraries/LDAPConnection.inc.php";

echo _("DROPPING:DATABASE") . $mysql_dbname . " ...\n";
$create_q = sprintf('DROP DATABASE IF EXISTS %s', $mysql_dbname);
$create_r = mysql_query($create_q);

echo _("DELETING:LDAP:MAXUID")."\n";
$del = ldap_delete($ldapc, $maxuiddn);

echo _("DONE")."\n";

?>
