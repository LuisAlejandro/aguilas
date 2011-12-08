<?php

include "config.php";
include "themes/$app_theme/header.php";
include "Functions.php";

echo _("##CREATING:DATABASE##") . $mysql_dbname . " ...";
include "CreateDatabase.php";

echo _("##CREATING:TABLE:RESETPASSWORD##");
include "CreatePasswordTable.php";

echo _("##CREATING:TABLE:NEWUSER##");
include "CreateUserTable.php";

echo _("##CREATING:LDAP:MAXUID##");
include "CreateMaxUIDEntry.php";

echo _("##DONE##");

include "themes/$app_theme/footer.php";

?>
