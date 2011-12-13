<?php

include_once "config.php";
include_once "themes/$app_theme/header.php";
include_once "Functions.php";

echo _("##CREATING:DATABASE##") . $mysql_dbname . " ...";
include_once "CreateDatabase.php";

echo _("##CREATING:TABLE:RESETPASSWORD##");
include_once "CreatePasswordTable.php";

echo _("##CREATING:TABLE:NEWUSER##");
include_once "CreateUserTable.php";

echo _("##CREATING:LDAP:MAXUID##");
include_once "CreateMaxUIDEntry.php";

echo _("##DONE##");

include_once "themes/$app_theme/footer.php";

?>
