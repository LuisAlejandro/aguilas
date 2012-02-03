<?php

putenv("LC_ALL=$app_locale.UTF-8");
putenv("LANG=$app_locale.UTF-8");
putenv("LANGUAGE=$app_locale");
setlocale(LC_ALL, "$app_locale.UTF-8");
bindtextdomain("messages", "locale");
textdomain("messages");

?>
