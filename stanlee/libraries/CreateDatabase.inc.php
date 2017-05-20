<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die("ERROR");
}

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./libraries/MYSQLConnection.inc.php";
require_once "./libraries/Functions.inc.php";

$create_q = sprintf('CREATE DATABASE IF NOT EXISTS %s', mysql_real_escape_string($mysql_dbname));

$create_r = AssistedMYSQLQuery($create_q);

?>
