<?php

$create_q = 'CREATE TABLE NewUser ( '
                .'main_id INT NOT NULL AUTO_INCREMENT, '
                .'uid VARCHAR(256) NOT NULL, '
                .'givenName VARCHAR(256) NOT NULL, '
                .'sn VARCHAR(256) NOT NULL, '
                .'mail VARCHAR(256) NOT NULL, '
                .'userPassword VARCHAR(30) NOT NULL, '
                .'description VARCHAR(256) NOT NULL, '
                .'token VARCHAR(256) NOT NULL, '
                .'PRIMARY KEY(main_id))';

$create_r = mysql_query($create_q)
        or die (
        '<div class="error">'
        . _("Ocurrió un error creando la tabla 'NewUser': ")
        . mysql_error($mysqlc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

?>