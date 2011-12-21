<?php

$allowed_ops = "install";

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";

// Prevent to be loaded from the webserver
if (array_key_exists('REMOTE_ADDR', $_SERVER)&&!isset($_SERVER['argc'])) {
    die("ERROR");
}

require_once "./libraries/MYSQLConnection.inc.php";
require_once "./libraries/LDAPConnection.inc.php";

echo _("CREATING:DATABASE") . $mysql_dbname . " ...\n";
$create_q = sprintf('CREATE DATABASE IF NOT EXISTS %s', $mysql_dbname);
$create_r = mysql_query($create_q);

echo _("CREATING:TABLE:RESETPASSWORD")."\n";
$create_q = 'CREATE TABLE ResetPassword ( '
        . 'main_id INT NOT NULL AUTO_INCREMENT, '
        . 'uid VARCHAR(256) NOT NULL, '
        . 'mail VARCHAR(256) NOT NULL, '
        . 'token VARCHAR(256) NOT NULL, '
        . 'description VARCHAR(256) NOT NULL, '
        . 'PRIMARY KEY(main_id))';
$create_r = mysql_query($create_q);

echo _("CREATING:TABLE:NEWUSER")."\n";
$create_q = 'CREATE TABLE NewUser ( '
        . 'main_id INT NOT NULL AUTO_INCREMENT, '
        . 'uid VARCHAR(256) NOT NULL, '
        . 'givenName VARCHAR(256) NOT NULL, '
        . 'sn VARCHAR(256) NOT NULL, '
        . 'mail VARCHAR(256) NOT NULL, '
        . 'userPassword VARCHAR(30) NOT NULL, '
        . 'description VARCHAR(256) NOT NULL, '
        . 'token VARCHAR(256) NOT NULL, '
        . 'PRIMARY KEY(main_id))';
$create_r = mysql_query($create_q);

echo _("CREATING:LDAP:MAXUID")."\n";
$newdn = $maxuiddn;
$in['sn'] = "maxUID";
$in['cn'] = "maxUID";
$in['uid'] = "maxUID";
$in['uidNumber'] = $maxuidstart;
$in['objectClass'][0] = "extensibleObject";
$in['objectClass'][1] = "person";
$in['objectClass'][2] = "top";
$add = ldap_add($ldapc, $newdn, $in);

echo _("DONE")."\n";

?>
