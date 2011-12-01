<?php

$allowed_ops = array("obj_a", "val_a", "who_a", "cn_a");

include "config.php";
include "Parameters.php";
include "LDAPConnection.php";
include "Functions.php";

// Pre-Processing the strings comming from ajax $_GET
$who_a = str_replace("__%_%_%__", "=", $who_a);
$who_a = str_replace("__@_@_@__", ",", $who_a);

// USER INPUT VALIDATION ------------------------------------------------------- 
// Someone tried to pass other parameter than $obj_a, $val_a,
// $who_a or $cn_a
if (!isset($obj_a) || !isset($val_a) || !isset($who_a) || !isset($cn_a)) {

    echo _("¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.");

// One or more of the fields was left empty
} elseif ($obj_a == '' || $val_a == '' || $who_a == '' || $cn_a == '') {

    echo _("No puedes dejar campos vacíos.");

// The first name has improper characters
} elseif ($obj_a == "givenName" && preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $val_a) == 0) {

    echo _("Tu nombre contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).");

// The last name has improper characters
} elseif ($obj_a == "sn" && preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/", $val_a) == 0) {

    echo _("Tu apellido contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).");

// Not a valid e-mail
} elseif ($obj_a == "mail" && preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $val_a) == 0) {

    echo _("El Correo Electrónico proporcionado no es válido. Verifica que lo hayas escrito correctamente.");
    
} else {

    // VALIDATION PASSED -------------------------------------------------------
    // Building $in array that we pass to ldap_modify
    $in["cn"] = $cn_a;
    $in[$obj_a] = $val_a;

    // Executing the modification of the entry provided
    $r = ldap_modify($ldapc, $who_a, $in)
            or die(
            _("Hubo un error modificando entradas del LDAP: ")
            . ldap_error($ldapc)
            . "."
            );

    // If the modification was a success
    if ($r) {
        // We log the event
        $log_location = __DIR__ . "/logs/" . $log_file . ".log";
        $log_string = "[" . $time_today . "]: "
                    . _("Se ha modificado el atributo ") . $obj_a
                    . _(" del usuario ") . $who_a . ".\n";
        $log_write = file_put_contents($log_location, $log_string, FILE_APPEND | LOCK_EX);
        // We output the modified value so that the form updates through ajax
        echo $val_a;
    }
    
}

// Closing the connection
$ldapx = ldap_close($ldapc)
        or die(
        _("Hubo un error cerrando la conexión con el LDAP: ")
        . ldap_error($ldapc)
        . "."
    );

?>