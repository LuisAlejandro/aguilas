<?php

$op_permitidas=array("uid","mail","image_captcha");
include "header.php";
include "get_post.php";
include "conectar_db_ldap.php";
include "conectar_db_mysql.php";
include "functions.php";

?>

<h2>Estado de la Solicitud</h2>

<?php

//Verificamos si la tabla "recuperar_password" existe
$probar=mysql_query('DESCRIBE recuperar_password');
//Si no existe, la creamos
if(($probar==FALSE)){include "crear_password_table.php";}
//Generamos el token de confirmación
$token = md5(mt_rand()."-".time()."-".$_SERVER['REMOTE_ADDR']."-".mt_rand());
//Generamos un pequeño string de descripción que contiente la dirección IP del solicitante
$description = "Solicitado por ".$_SERVER['REMOTE_ADDR'];
//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

if(!isset($uid)||!isset($mail)||!isset($token)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$mail==''||$token==''||$image_captcha==''){

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

if(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail)==0){

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
El nombre de usuario contiene caracteres inválidos. Sólo se permiten letras (mayúsculas y minúsculas), números y guión inferior (_).
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

}else{

//Consultamos si ya existe la cuenta en el LDAP
$cuenta_limitar = array("mail","uid");
$cuenta_filtro_buscar="(&(mail=$mail)(uid=$uid))";
$cuenta_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $cuenta_filtro_buscar, $cuenta_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$cuenta_entradas_ldap = ldap_get_entries($ldapc, $cuenta_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

if($cuenta_entradas_ldap["count"]==0){

?>
	
<div class="error">
No existe un usuario que coincida con el correo electrónico proporcionado.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($cuenta_entradas_ldap["count"]>1){

?>
	
<div class="error">
La base de datos está corrupta para éste usuario (hay más de una entrada con el mismo nombre de usuario y correo) por favor envía un correo a plataforma-colaborativa@canaima.softwarelibre.gob.ve con éste error. Lo sentimos, para continuar debemos eliminar este usuario.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($cuenta_entradas_ldap["count"]==1){

//Query para insertar la petición en la tabla MYSQL temporal
$query = "INSERT INTO recuperar_password (uid, mail, token, description) VALUES ('" . $uid . "', '" . $mail . "', '" . $token . "', '" . $description . "')";
$result = mysql_query($query) or die ('<div class="error">Hubo un error en la escritura de la Base de Datos "recuperar_password": ' . mysql_error() . '<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

//Preparamos el correo de confirmación
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$subject = "Reestablecimiento de Contraseña en la Plataforma Colaborativa de Canaima";
$file_url = "http://".$URL_base."/generar_password_activado.php";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Reestablecimiento de Contraseña en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Has recibido éste correo electrónico porque alguien solicitó un reestablecimiento de contraseña para el usuario "<strong>'.$uid.'</strong>"en la Plataforma Colaborativa de Canaima. Haz click en el siguiente enlace para confirmar tu petición.</p><p><a href="'."$file_url?mail=$mail&uid=$uid&token=$token".'>CONFIRMAR</a></p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/cambio_password";
$lf->write("$time_today:Se ha enviado un email de confirmación (Nueva Contraseña) a $mail (uid:$uid;token:$token).\n\n",$registro_email);

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
La solicitud de reestablecimiento de contraseña ha sido procesada correctamente. Revisa tu correo electrónico (<?php echo $mail; ?>) para finalizar el proceso.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

}

ldap_close($ldapc);

mysql_close($mysql_connection);

include "footer.php";

?>
