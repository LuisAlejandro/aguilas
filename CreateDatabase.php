<?php

$create_q = 'CREATE DATABASE aguilas';

$create_r = mysql_query($create_q)
        or die (
        '<div class="error">'
        . _("Ocurrió un error creando la base de datos 'aguilas': ")
        . mysql_error($mysqlc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

?>
