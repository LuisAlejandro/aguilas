<?php

/**********************************
 * CONFIGURATION FILE FOR AGUILAS *
 **********************************/

// BRANDING
// name of the Application. e.g. "Debian User Management"
$app_name = "@@APP_NAME@@";
// Operator
// the person or group responsible for managing the application
$app_operator = "@@APP_OPERATOR@@";
// Operator e-mail
// will appear as sender in all operation e-mails to registered users
$app_mail = "@@APP_MAIL@@";
// Application language
// must be available in the locales/ folder
$app_locale = "@@APP_MAIL@@";
// Application theme
// must be available in the themes/ folder
$app_theme = "@@APP_THEME@@";
// Application URL
// the public address of the aplication, *essential* for including the correct
// address on the confirmation e-mails
$app_url = "@@APP_URL@@";
// Application links
// an array of link_title => link of the applications connected
// to the LDAP and whose registration process have been delegated to AGUILAS
// this will appear on the main menu
$app_links = array("APP1" => "http://$app_url", "APP2" => "http://$app_url", "APP3" => "http://$app_url", "APP4" => "http://$app_url");

// MYSQL
// IP or Domain of the server where the MYSQL database is located
$mysql_host = "@@MYSQL_HOST@@";
// Database name (MUST EXIST!)
$mysql_dbname = "@@MYSQL_DBNAME@@";
// User with permissions to read and create tables on the database
$mysql_user = "@@MYSQL_USER@@";
// Password for the user
$mysql_pass = "@@MYSQL_PASS@@";

// LDAP
// IP or Domain of the server where the LDAP service is located
$ldap_server = "@@LDAP_SERVER@@";
// DN with read-write priviledges on the LDAP server
$ldap_dn = "@@LDAP_DN@@";
// Password for the writer DN
$ldap_pass = "@@LDAP_PASS@@";
// Base DN to perform searches and include new users
$ldap_base = "@@LDAP_BASE@@";
// Asociative array containing Group Name => Group ID (gidNumber)
// this is used to parse the name of the group according to the
// gidNumber assigned to each LDAP DN entry
$ldap_gid = array("people" => "100", "admin" => "200");
// The default group assigned to new users
$ldap_default_group = "people";
?>