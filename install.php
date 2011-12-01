<?php

include "config.php";
echo $app_theme;
include "themes/$app_theme/header.php"

echo _("Creando base de datos 'aguilas' ...");
include "CreateDatabase.php";

echo _("Creando tabla para las peticiones de reestablecimiento de contraseña ...");
include "CreatePasswordTable.php";

echo _("Creando tabla para las peticiones de nuevos usuarios ...");
include "CreateUserTable.php";

echo _("Creando entrada MaxUID en el LDAP ...");
include "CreateMaxUIDEntry.php";

echo _("¡HECHO!");

include "themes/$app_theme/footer.php"

?>
