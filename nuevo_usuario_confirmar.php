<?php

$op_permitidas=array("uid","mail","token","givenName","sn","userPassword","userPasswordBis","image_captcha");
include "header.php";
include "get_post.php";
include "conectar_db_ldap.php";
include "conectar_db_mysql.php";
include "functions.php";

?>

<h2>Estado de la Solicitud</h2>

<?php

//Verificamos si la tabla "nuevo_usuario" existe
$probar=mysql_query('DESCRIBE nuevo_usuario');
//Si no existe, la creamos
if(($probar==FALSE)){include "crear_user_table.php";}
//Generamos el token de confirmación
$token = md5(mt_rand()."-".time()."-".$_SERVER['REMOTE_ADDR']."-".mt_rand());
//Generamos un pequeño string de descripción que contiente la dirección IP del solicitante
$description = "Solicitado por ".$_SERVER['REMOTE_ADDR'];
//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

// ---------- VALIDACIONES --------------------------------------------

if(!isset($uid)||!isset($givenName)||!isset($sn)||!isset($mail)||!isset($userPassword)||!isset($userPasswordBis)||!isset($description)||!isset($token)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$givenName==''||$sn==''||$mail==''||$userPassword==''||$description==''||$token==''||$image_captcha==''){

?>
	
<div class="error">
Hubo un error en el llenado del Formulario.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{
	
//---------- CAPTCHA -------------------------------------------------
//Iniciamos sesión (cookies)
session_start();
if(isset($_SESSION['captcha'])){
$session_captcha = $_SESSION['captcha'];
}else{
$session_captcha = "CADUCO";
}
//Convertimos a MD5 lo que el usuario puso en el formulario
$image_captcha = md5($image_captcha);
//---------- CAPTCHA -------------------------------------------------

//Consultamos si ya existe el uid en el LDAP
$uid_limitar = array("uid");
$uid_filtro_buscar="(uid=$uid)";
$uid_verificar_ldap = ldap_search($ldapc, $ldap_base, $uid_filtro_buscar, $uid_limitar);
$uid_entradas_ldap = ldap_get_entries($ldapc, $uid_verificar_ldap);

//Consultamos si ya existe la mail en el LDAP
$mail_limitar = array("mail");
$mail_filtro_buscar="(mail=$mail)";
$mail_verificar_ldap = ldap_search($ldapc, $ldap_base, $mail_filtro_buscar, $mail_limitar);
$mail_entradas_ldap = ldap_get_entries($ldapc, $mail_verificar_ldap);

if($uid_entradas_ldap["count"]>0){

?>
	
<div class="error">
El nombre de usuario escogido ya existe.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($mail_entradas_ldap["count"]>0){

?>
	
<div class="error">
El correo indicado ya está asociado a un usuario.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($session_captcha=="CADUCO"){

?>

<div class="error">
La página ha caducado.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($session_captcha<>$image_captcha){

?>

<div class="error">
Llenaste incorrectamente la imagen de verificación (CAPTCHA). Debes escribir exactamente lo que dice la imagen en el campo de texto.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($userPassword<>$userPasswordBis){

?>

<div class="error">
Las Contraseñas no coinciden.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-z0-9_]+$/",$uid)==0){

?>

<div class="error">
El nombre de usuario contiene caracteres inválidos. Sólo se permiten letras (mayúsculas y minúsculas), números y guión inferior (_).
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-z0-9]+$/",$userPassword)==0){

?>

<div class="error">
La contraseña contiene caracteres inválidos. Sólo se permiten letras (mayúsculas y minúsculas) y números.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/",$givenName)==0){

?>

<div class="error">
Tu nombre contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ\s?]+$/",$sn)==0){

?>

<div class="error">
Tu apellido contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif((strlen($userPassword)<8)||(strlen($userPassword)>20)){

?>

<div class="error">
La longitud de la contraseña debe ser de 8 caracteres mínimo y 20 caracteres máximo.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail)==0){

?>

<div class="error">
El Correo Electrónico proporcionado no es válido. Solicita una cuenta nueva con un correo electrónico correcto.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{

//Query para insertar la petición en la tabla MYSQL temporal
$query = "INSERT INTO nuevo_usuario (uid, givenName, sn, mail, userPassword, description, token) VALUES ('" . $uid . "', '" . $givenName . "', '" . $sn . "', '" . $mail . "', '" . $userPassword . "', '" . $description . "', '" . $token . "')";
$result = mysql_query($query) or die ('<div class="error">Hubo un error en la escritura de la Base de Datos "nuevo_usuario": ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

//Preparamos el correo de confirmación
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$subject = "Activación de Nuevo Usuario en la Plataforma Colaborativa de Canaima";
$file_url = "http://".$URL_base."/".basename(dirname(__FILE__))."/nuevo_usuario_activado.php";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Activación de Nuevo Usuario en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Hola, <strong>'.$givenName.'</strong>.</p><p>Has recibido éste correo electrónico porque hiciste una petición de Nuevo Usuario en la Plataforma Colaborativa de Canaima. Haz click en el siguiente enlace para confirmar tu petición.</p><p><a href="'."$file_url?uid=$uid&token=$token".'">CONFIRMAR</a></p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/nuevo_usuario";
$lf->write("$time_today:Se ha enviado un email de confirmación (Nuevo Usuario) a $mail (uid:$uid;token:$token).\n\n",$registro_email);

}else{

?>

<div class="error">
Hubo un error enviando el correo de confirmación. Por favor intenta de nuevo más tarde.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}

?>

<div class="exito">
La solicitud de la cuenta <strong><?php echo $uid; ?></strong> ha sido procesada correctamente. Revisa tu correo electrónico (<?php echo $mail; ?>) para finalizar el proceso de registro y confirmar tu dirección.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

ldap_close($ldapc);

mysql_close($mysql_connection);

include "footer.php";

?>
