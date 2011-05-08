<?php

$query = 'CREATE TABLE nuevo_usuario ( '
.'main_id INT NOT NULL AUTO_INCREMENT, '
.'uid VARCHAR(256) NOT NULL, '
.'givenName VARCHAR(256) NOT NULL, '
.'sn VARCHAR(256) NOT NULL, '
.'mail VARCHAR(256) NOT NULL, '
.'userPassword VARCHAR(30) NOT NULL, '
.'description VARCHAR(256) NOT NULL, '
.'token VARCHAR(256) NOT NULL, '
.'PRIMARY KEY(main_id))';

$result = mysql_query($query) or die('error_user:' . mysql_error());

?>
