<?php 

$op_permitidas=array("uid","mail","token");
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
//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

// ---------- VALIDACIONES --------------------------------------------

if(!isset($uid)||!isset($mail)||!isset($token)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
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

}elseif(preg_match("/^[A-Za-z0-9]+$/",$token)==0){

?>

<div class="error">
El token de confirmación es inválido.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{
	
$query = "SELECT * FROM recuperar_password WHERE mail='".$mail."' AND uid='".$uid."' AND token='".$token."'";

$result = mysql_query($query) or die('<div class="error">Hubo un error en la lectura de la Base de Datos "recuperar_password": ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$result_num=mysql_num_rows($result);

if($result_num==0){
	
?>

<div class="error">
Token de confirmación Inválido
<br /><br />
<a href="javascript:history.back(1);">Volver</a>
</div>

<?php

}elseif($result_num>1){

?>

<div class="error">
Hubo un error en la consulta, al parecer existen tokens iguales en la Base de Datos... Lo cual es imposible. Envía éste error a plataforma-colaborativa@canaima.softwarelibre.gob.ve y vuelve a hacer una petición de cambio de password. Disculpa las molestias causadas.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($result_num==1){

while($row = mysql_fetch_array($result, MYSQL_ASSOC)){

$mail=$row['mail'];
$uid=$row['uid'];
//Generamos la nueva contraseña
$in['userPassword'] = substr(md5(md5(mt_rand()."-".$mail."+".$uid."+".time()."-".mt_rand())),0,8);
$nuevaContrasenia=$in['userPassword'];

//Nos traemos el dn correspondiente a la cuenta que coincida con uid y mail
$pass_limitar = array("dn");
$pass_filtro_buscar = "(&(mail=$mail)(uid=$uid))";
$pass_verificar_ldap = ldap_search($ldapc, $ldap_base, $pass_filtro_buscar, $pass_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$pass_entradas_ldap = ldap_get_entries($ldapc, $pass_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

if($pass_entradas_ldap['count']==0){

?>

<div class="error">
El usuario no existe.
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

$r = ldap_modify($ldapc,$pass_entradas_ldap['0']['dn'],$in) or die ('<div class="error">Hubo un error modificando entradas del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$subject = "Reestablecimiento de Contraseña en la Plataforma Colaborativa de Canaima";
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Reestablecimiento de Contraseña en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Hola, tu nueva contraseña es "<strong>'.$nuevaContrasenia.'</strong>".</p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/cambio_password";
$lf->write("$time_today:Se ha enviado un email de éxito (Contraseña Cambiada) a $mail (uid:$uid;token:$token).\n\n",$registro_email);

}else{

?>

<div class="error">
Hubo un error enviando el correo de cambio de contraseña. Pero no te preocupes, tu contraseña ya fue cambiada.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}

//Borramos el registro temporal en MYSQL
$query = "DELETE FROM recuperar_password WHERE mail='".$mail."' AND uid='".$uid."' AND token='".$token."'";
$result2 = mysql_query($query) or die('<div class="error">Hubo un error en la escritura de la Base de Datos "recuperar_password": ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

?>

<div class="exito">
¡Éxito! Tu nueva contraseña es <strong><?php echo $nuevaContrasenia; ?></strong>.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

}

}

ldap_close($ldapc);

mysql_close($mysql_connection);

include "footer.php";

?>
