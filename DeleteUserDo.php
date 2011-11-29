<?php 

$allowed_ops=array("uid","mail","userPassword","image_captcha");
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "Functions.php";

?>

<h2><?= _("Estado de la Solicitud") ?></h2>

<?php

//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

if(!isset($uid)||!isset($mail)||!isset($userPassword)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$mail==''||$userPassword==''||$image_captcha==''){

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

}elseif(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail)==0){

?>

<div class="error">
El Correo Electrónico proporcionado no es válido. Cambia tu cuenta
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{

//Consultamos si ya existe la cuenta en el LDAP
$eliminar_limitar = array("dn");
$eliminar_filtro_buscar = "(&(mail=".$mail.")(userPassword=".$userPassword.")(uid=".$uid."))";
$eliminar_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $eliminar_filtro_buscar, $eliminar_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$eliminar_entradas_ldap = ldap_get_entries($ldapc, $eliminar_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$total_usuarios=$eliminar_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

if($total_usuarios>1){
	
?>

<div class="error">
¡OOoops! Existe más de un usuario que coincide con esos datos. Por favor envía un correo a plataforma-colaborativa@canaima.softwarelibre.gob.ve para solucionar manualmente este percance.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($total_usuarios==0){
	
?>

<div class="error">
Los datos proporcionados no coinciden.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($total_usuarios==1){

$dn=$eliminar_entradas_ldap[0]["dn"];

$r=ldap_delete($ldapc,$dn) or die ('<div class="error">Hubo un error eliminando entradas del LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$subject = "Usuario Eliminado en la Plataforma Colaborativa de Canaima";
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Usuario Eliminado en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Estimado usuario "<strong>'.$uid.'</strong>", tu cuenta ha sido eliminada satisfactoriamente.</p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/eliminar_usuario";
$lf->write("$time_today:Se ha enviado un email de éxito (Usuario Eliminado) a $mail (uid:$uid).\n\n",$registro_email);

}else{

?>

<div class="error">
Hubo un error enviando el correo de notificación. Pero no te preocupes, tu usuario ya fué eliminado.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}

?>

<div class="exito">
El usuario "<strong><?php echo $uid; ?></strong>" ha sido eliminado.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

}

// Closing the connection
$ldapx = ldap_close($ldapc)
        or die (
        '<div class="error">'
        . _("Hubo un error cerrando la conexión con el LDAP: ")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

include "themes/$app_theme/footer.php";

?>
