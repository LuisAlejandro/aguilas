<?php

$mysqlc = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
        or die (
        '<div class="error">'
        . _("Ocurrió un error de conexión con la Base de Datos MYSQL.")
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );

$mysqls = mysql_select_db($mysql_dbname);

if (!$mysqls) {

    include "CreateDatabase.php";
    
    $mysqls = mysql_select_db($mysql_dbname)
        or die (
        _("Imposible selecionar la base de datos ")
        . '"' . $mysql_dbname . '": '
        . mysql_error($mysqlc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        . file_get_contents("themes/$app_theme/footer.php")
        );
}

?>