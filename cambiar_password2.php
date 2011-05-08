<?php 

$op_permitidas=array("uid","mail","userPasswordOld","userPassword","userPasswordBis","image_captcha");
include "header.php";
include "get_post.php";
include "conectar_db_ldap.php";
include "functions.php";

?>

<h2>Estado de la Solicitud</h2>

<?php

//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

// ---------- VALIDACIONES --------------------------------------------

if(!isset($uid)||!isset($mail)||!isset($userPasswordOld)||!isset($userPassword)||!isset($userPasswordBis)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$mail==''||$userPasswordOld==''||$userPassword==''||$userPasswordBis==''||$image_captcha==''){

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

if($session_captcha=="CADUCO"){

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

}elseif(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail)==0){

?>

<div class="error">
El Correo Electrónico proporcionado no es válido. Verifica si lo has escrito correctamente.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-z0-9_]+$/",$uid)==0){

?>

<div class="error">
El nombre de usuario es inválido.
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

}elseif($userPassword<>$userPasswordBis){

?>

<div class="error">
Las Contraseñas no coinciden.
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

}else{

//Consultamos si ya existe la cuenta en el LDAP
$pass_limitar = array("dn");
$pass_filtro_buscar = "(&(mail=".$mail.")(userPassword=".$userPasswordOld.")(uid=".$uid."))";
$pass_verificar_ldap = ldap_search($ldapc, $ldap_base, $pass_filtro_buscar, $pass_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$pass_entradas_ldap = ldap_get_entries($ldapc, $pass_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$total_usuarios=$pass_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

if($pass_entradas_ldap['count']==0){

?>

<div class="error">
Los datos proporcionados no coinciden.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($pass_entradas_ldap['count']>1){

?>

<div class="error">
Existe una inconsistencia en la Base de Datos. Hay dos o más cuentas que comparten el mismo nombre de usuario y correo electrónico, lo cual es inapropiado. Para poder realizar el cambio de contraseña debes eliminar tu cuenta de usuario y crear una nueva.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($pass_entradas_ldap['count']==1){

$in['userPassword'] = $userPassword;

$r = ldap_modify($ldapc,$pass_entradas_ldap['0']['dn'],$in) or die ('<div class="error">Hubo un error modificando entradas del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$subject = "Cambio de Contraseña en la Plataforma Colaborativa de Canaima";
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Reestablecimiento de Contraseña en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Hola! Tu contraseña en la Plataforma Colaborativa Canaima ha sido cambiada.</p><br /><br /><p>Equipo del Proyecto Canaima GNU/Linux</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/cambio_password";
$lf->write("$time_today:Se ha enviado un email de éxito (Contraseña Cambiada) a $mail (uid:$uid).\n\n",$registro_email);

}else{

?>

<div class="error">
Hubo un error enviando el correo de cambio de contraseña. Pero no te preocupes, tu contraseña ya fue cambiada.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}

?>

<div class="exito">
¡Éxito! Tu contraseña ha sido cambiada.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

}

ldap_close($ldapc);

include "footer.php";

?>
