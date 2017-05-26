<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die("ERROR");
}

require_once "./setup/config.php";

putenv("LC_ALL=$app_locale.UTF-8");
putenv("LANG=$app_locale.UTF-8");
putenv("LANGUAGE=$app_locale");
setlocale(LC_ALL, "$app_locale.UTF-8");
bindtextdomain("messages", "locale");
bind_textdomain_codeset('messages','UTF-8');
textdomain("messages");

?>
