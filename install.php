<?php

include "config.php";
include "themes/$app_theme/header.php";
include "Functions.php";

echo _("Creando base de datos ") . $mysql_dbname . " ...";
include "CreateDatabase.php";

echo _("Creando tabla para las peticiones de reestablecimiento de contraseña ...");
include "CreatePasswordTable.php";

echo _("Creando tabla para las peticiones de nuevos usuarios ...");
include "CreateUserTable.php";

echo _("Creando entrada MaxUID en el LDAP ...");
include "CreateMaxUIDEntry.php";

echo _("¡HECHO!");

include "themes/$app_theme/footer.php";

?>
