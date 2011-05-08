<?php

$op_permitidas=array("obj_a","val_a","quien_a","cn_a");
include "get_post.php";
include "conectar_db_ldap.php";
include "functions.php";

//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

$quien_a=str_replace("__%_%_%__","=",$quien_a);
$quien_a=str_replace("__@_@_@__",",",$quien_a);

// ---------- VALIDACIONES --------------------------------------------

if(!isset($obj_a)||!isset($val_a)||!isset($quien_a)||!isset($cn_a)){

echo "¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.";

}elseif($obj_a==''||$val_a==''||$quien_a==''||$cn_a==''){

echo "No puedes dejar campos vacíos.";

}elseif($obj_a=="givenName"&&preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/",$val_a)==0){

echo "Tu nombre contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).";

}elseif($obj_a=="sn"&&preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/",$val_a)==0){

echo "Tu apellido contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).";

}elseif($obj_a=="mail"&&preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $val_a)==0){

echo "El Correo Electrónico proporcionado no es válido. Verifica que lo has ecrito correctamente.";

}else{

$in["cn"]=$cn_a;
$in[$obj_a]=$val_a;

$r = ldap_modify($ldapc,$quien_a,$in) or die ('Hubo un error modificando entradas del LDAP: ' . ldap_error($ldapc) . '.');

//Guardamos el evento en el log
$lf = new logfile();
$log_ajax= dirname(__FILE__). "/logs/ajax";
$lf->write("$time_today:Se ha modificado el atributo \"$obj_a\" de usuario \"$quien_a\".\n",$log_ajax);

echo $val_a;

}

ldap_close($ldapc);

?>
