<?php

$op_permitidas=array("mail","image_captcha");
include "header.php";
include "get_post.php";
include "conectar_db_mysql.php";
include "functions.php";

?>

<h2>Estado de la Solicitud</h2>

<?php

//Verificamos si la tabla "nuevo_usuario" existe
$probar=mysql_query('DESCRIBE nuevo_usuario');
//Si no existe, la creamos
if(($probar==FALSE)){include "crear_user_table.php";}
//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

if(!isset($mail)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($mail==''||$image_captcha==''){

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
El Correo Electrónico proporcionado no es válido. Solicita una cuenta nueva con un correo electrónico correcto.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{
	
$query = "SELECT * FROM nuevo_usuario WHERE mail='".$mail."' ORDER BY token DESC LIMIT 0,1";
$result = mysql_query($query) or die('<div class="error">Hubo un error en la lectura de la Base de Datos "nuevo_usuario": ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$result_num=mysql_num_rows($result);

if($result_num==0){
	
?>

<div class="error">
No se ha hecho petición de crear un usuario con esa dirección de correo. Intenta creando el usuario de nuevo.
<br /><br />
<a href="javascript:history.back(1);">¿Volver?</a>
</div>

<?php

}else{

while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	
$uid=$row['uid'];
$token=$row['token'];
$givenName=$row['givenName'];

$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$subject = "Activación de Nuevo Usuario en la Plataforma Colaborativa de Canaima";
$file_url = "http://".$URL_base."/nuevo_usuario_activado.php";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Activación de Nuevo Usuario en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Hola, <strong>'.$givenName.'</strong>.</p><p>Has recibido éste correo electrónico porque hiciste una petición de Nuevo Usuario en la Plataforma Colaborativa de Canaima. Haz click en el siguiente enlace para confirmar tu petición.</p><p><a href="'."$file_url?uid=$uid&token=$token".'">CONFIRMAR</a></p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/nuevo_usuario";
$lf->write("$time_today:Se ha re-enviado un email de confirmación (Nuevo Usuario) a $mail (uid:$uid;token:$token).\n\n",$registro_email);

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

}

}

mysql_close($mysql_connection);

include "footer.php";

?>
