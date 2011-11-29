<?php

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