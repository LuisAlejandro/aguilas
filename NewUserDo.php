<?php

$allowed_ops=array("uid","mail","token");
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "MYSQLConnection.php";
include "Functions.php";
include "CreateMaxUIDEntry.php";

?>

<h2><?= _("Estado de la Solicitud") ?></h2>

<?php

//Verificamos si la tabla "nuevo_usuario" existe
$probar=mysql_query('DESCRIBE nuevo_usuario');
//Si no existe, la creamos
if(($probar==FALSE)){include "CreateUserTable.php";}
//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");
//Guardamos la IP del visitante
$IP = $_SERVER['REMOTE_ADDR'];

// ---------- USER INPUT VALIDATION -------------------------------------------- 

if(!isset($uid)||!isset($token)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$token==''){

?>
	
<div class="error">
Hubo un error en el llenado del Formulario.
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
	
//Consultamos en la base de datos temporal si hay un registro con los $uid y $token
//que vienen de $_GET
$query = "SELECT * FROM nuevo_usuario WHERE uid='".$uid."' AND token='".$token."'";
$result = mysql_query($query) or die('<div class="error">Hubo un error en la lectura de la Base de Datos "nuevo_usuario": ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$result_num=mysql_num_rows($result);

//Si no hay resultados, la consulta está mal hecha
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
Hubo un error en la consulta, al parecer existen tokens iguales en la Base de Datos... Lo cual es imposible. Envía éste error a plataforma-colaborativa@canaima.softwarelibre.gob.ve y vuelve a hacer una petición nuevo usuario. Disculpa las molestias causadas.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($result_num==1){
	
while($row = mysql_fetch_array($result, MYSQL_ASSOC)){

//Consultamos si ya existe el uid en el LDAP
$uid_limitar = array("uid");
$uid_filtro_buscar="(uid=$uid)";
$uid_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $uid_filtro_buscar, $uid_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$uid_entradas_ldap = ldap_get_entries($ldapc, $uid_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

if($uid_entradas_ldap['count']==1){

?>

<div class="error">
El usuario ya existe. Al parecer alguien más creó el usuario mientras tu revisabas tu correo de confirmación. Lo sentimos, debes hacer la petición de nuevo (con otro nombre de usuario).
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid_entradas_ldap['count']>1){

?>

<div class="error">
Existe una inconsistencia en la Base de Datos. Hay dos o más cuentas que comparten el mismo nombre de usuario y correo electrónico, lo cual es inapropiado. Por favor informa de éste error al correo platadorma
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid_entradas_ldap['count']==0){
	
$new_limitar = array("uidNumber");
$new_filtro_buscar="(uid=maxUID)";
$new_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $new_filtro_buscar, $new_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$new_entradas_ldap = ldap_get_entries($ldapc, $new_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$max_uidnumber=$new_entradas_ldap[0]['uidnumber'][0];

//Especificamos el dn del nuevo usuario
$ldap_nuevo="uid=".$row['uid'].",".$ldap_base_nuevo;
//Llenamos el array $in con los datos del nuevo usuario
$in['givenName'] = $row['givenName'];
$in['sn'] = $row['sn'];
$in['cn'] = $row['givenName']." ".$row['sn'];
$in['uid'] = $row['uid'];
$in['mail'] = $row['mail'];
$mail=$in['mail'];
$in['uidNumber'] = $max_uidnumber+1;
$in['gidNumber'] = $ldap_gid['Gente'];
$in['userPassword'] = $row['userPassword'];
$in['homeDirectory'] = "/home/".$row['uid'];
$in['objectClass'][0] = "inetOrgPerson";
$in['objectClass'][1] = "posixAccount";
$in['objectClass'][2] = "top";
$in['objectClass'][3] = "person";
$in['objectClass'][4] = "shadowAccount";
$in['objectClass'][5] = "organizationalPerson";
$in['objectClass'][6] = "tracUser";
$in['tracperm'] = "WIKI_VIEW";
$in['loginShell'] = "/bin/false";
$in['description'] = "Entrada creada por el Sistema de Registro AGUILAS el ".$time_today.", a petición de ".$IP;

$in2['uidNumber'] = $in['uidNumber'];

//Hacemos el query con los datos
$r = ldap_add($ldapc,$ldap_nuevo,$in) or die ('<div class="error">Hubo un error insertando entradas al LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$uidmasuno = ldap_modify($ldapc, "uid=maxUID,".$ldap_base_nuevo, $in2) or die ('<div class="error">Hubo un error insertando entradas al LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

//Preparamos el correo de confirmación
$headers = "From: plataforma-colaborativa@canaima.softwarelibre.gob.ve\nContent-Type: text/html; charset=utf-8";
$subject = "Nuevo usuario en la Plataforma Colaborativa de Canaima";
$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8"><TITLE>Nuevo Usuario en la Plataforma Colaborativa de Canaima</TITLE><META NAME="GENERATOR" CONTENT="Canaima GNU/Linux"><META NAME="AUTHOR" CONTENT="Luis Alejandro Martínez Faneyth"></HEAD><BODY LANG="es-VE" DIR="LTR"><p>Tu nuevo usuario "<strong>'.$uid.'</strong>" ha sido activado.</p><br /><br /><p>Equipo de la Plataforma Colaborativa de Canaima</p></BODY></HTML>';

//Enviamos el correo
if(mail($mail,$subject,$body,$headers)){

//Guardamos el evento en el log
$lf = new logfile();
$registro_email= dirname(__FILE__). "/logs/nuevo_usuario";
$lf->write("$time_today:Se ha enviado un email de éxito (Nuevo Usuario Activado) a $mail (uid:$uid;token:$token).\n\n",$registro_email);

}else{

?>

<div class="error">
Hubo un error enviando el correo de Activación de Usuario. Pero no te preocupes, tu usuario ya fué activado.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}

//Borramos el registro temporal en MYSQL
$query = "DELETE FROM nuevo_usuario WHERE uid='".$uid."' AND token='".$token."'";
$result2 = mysql_query($query) or die('<div class="error">Hubo un error en la lectura de la Base de Datos nuevo_usuario: ' . mysql_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

?>

<div class="exito">
La cuenta <strong><?php echo $uid; ?></strong> ha sido activada correctamente. ¡Sigue Participando! Tu aporte es valioso.
<br /><br />
<a href="index.php">Portada</a>
</div>

<?php

}

}

}

}

$ldapx = ldap_close($ldapc)
        or die (
        '<div class="error">'
        . _("Hubo un error cerrando la conexión con el LDAP: ")
        . ldap_error($ldapc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

$mysqlx = mysql_close($mysqlc)
        or die (
        '<div class="error">'
        . _("Hubo un error cerrando la conexión con la Base de Datos MYSQL: ")
        . mysql_error($mysqlc)
        . '.<br /><br /><a href="javascript:history.back(1);">'
        . _("Atrás")
        . '</a></div>'
        );

include "themes/$app_theme/footer.php";

?>