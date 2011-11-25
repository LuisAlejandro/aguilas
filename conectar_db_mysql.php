<?php

include "config.php";

$mysql_connection = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die ('Error conectando a la base de datos MySQL: '.mysql_error());
$mysql_select_result = mysql_select_db($mysql_dbname) or die ('Imposible selecionar la base de datos Canaima: '.mysql_error());

?>
