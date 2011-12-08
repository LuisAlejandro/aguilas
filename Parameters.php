<?php

// We store today's date
$time_today = date("d-m-Y-H:i:s");

// Generate confirmation token to send by e-mail
$token = md5(mt_rand() . "-" . time() . "-" . $_SERVER['REMOTE_ADDR'] . "-" . mt_rand());

// A little description
$description = _("Entrada creada por $app_name a las " )
                    . $time_today . _("##PETITION##")
                    . $_SERVER['REMOTE_ADDR'];

foreach( $_POST as $key => $value ){
    $asign = "\$" . $key . "='" . $value . "';";
    if( in_array( $key, $allowed_ops )){
        eval($asign);
    }
}

foreach( $_GET as $key => $value ){
    $asign = "\$" . $key . "='" . $value . "';";
    if( in_array( $key, $allowed_ops )){
        eval($asign);
    }
}

?>