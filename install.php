<?php

include_once "config.php";
include_once "Locale.php";
include_once "MYSQLConnection.php";
include_once "LDAPConnection.php";

echo _("##CREATING:DATABASE##") . $mysql_dbname . " ...\n";
$create_q = 'CREATE DATABASE ' . $mysql_dbname;
$create_r = mysql_query($create_q);

echo _("##CREATING:TABLE:RESETPASSWORD##")."\n";
$create_q = 'CREATE TABLE ResetPassword ( '
        . 'main_id INT NOT NULL AUTO_INCREMENT, '
        . 'uid VARCHAR(256) NOT NULL, '
        . 'mail VARCHAR(256) NOT NULL, '
        . 'token VARCHAR(256) NOT NULL, '
        . 'description VARCHAR(256) NOT NULL, '
        . 'PRIMARY KEY(main_id))';
$create_r = mysql_query($create_q);

echo _("##CREATING:TABLE:NEWUSER##")."\n";
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

echo _("##CREATING:LDAP:MAXUID##")."\n";
$newdn = $maxuiddn;
$in['sn'] = "maxUID";
$in['cn'] = "maxUID";
$in['uid'] = "maxUID";
$in['uidNumber'] = $maxuidstart;
$in['objectClass'][0] = "extensibleObject";
$in['objectClass'][1] = "person";
$in['objectClass'][2] = "top";
$add = ldap_add($ldapc, $newdn, $in);

echo _("##DONE##")."\n";

?>
