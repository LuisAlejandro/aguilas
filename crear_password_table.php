<?php

$query = 'CREATE TABLE recuperar_password ( '
.'main_id INT NOT NULL AUTO_INCREMENT, '
.'uid VARCHAR(256) NOT NULL, '
.'mail VARCHAR(256) NOT NULL, '
.'token VARCHAR(256) NOT NULL, '
.'description VARCHAR(256) NOT NULL, '
.'PRIMARY KEY(main_id))';

$result = mysql_query($query) or die('error_password:' . mysql_error());

?>
