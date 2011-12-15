<?php
echo $app_locale;
putenv("LC_ALL=$app_locale");
setlocale(LC_ALL, $app_locale);
bindtextdomain("messages", "./locale");
textdomain("messages");

?>
