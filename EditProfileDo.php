<?php 

$allowed_ops=array("uid","userPassword","image_captcha");
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "Functions.php";

?>

<h2><?= _("Estado de la Solicitud") ?></h2>

<?php

//Guardamos la fecha del día de hoy
$time_today = date("d-m-Y-H:i:s");

// ---------- USER INPUT VALIDATION -------------------------------------------- 

if(!isset($uid)||!isset($userPassword)||!isset($image_captcha)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($uid==''||$userPassword==''||$image_captcha==''){

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
$edit_limitar = array("dn","givenName","sn","cn","uid","userPassword","mail","uidNumber","gidNumber");
$edit_filtro_buscar = "(&(userPassword=".$userPassword.")(uid=".$uid."))";
$edit_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $edit_filtro_buscar, $edit_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$edit_entradas_ldap = ldap_get_entries($ldapc, $edit_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$total_usuarios=$edit_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

if($edit_entradas_ldap['count']==0){

?>

<div class="error">
Los datos proporcionados no coinciden.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($edit_entradas_ldap['count']>1){

?>

<div class="error">
Existe una inconsistencia en la Base de Datos. Hay dos o más cuentas que comparten el mismo nombre de usuario, lo cual es inapropiado. Para poder continuar debes eliminar tu cuenta de usuario y crear una nueva.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($edit_entradas_ldap['count']==1){

$objeto_array=array("uid","uidNumber","givenName","sn","cn","mail","userPassword","gidNumber");
$etiqueta_array=array("Nombre de Usuario","Número de Usuario (ID)","Nombres","Apellidos","Nombre Completo","Correo Electrónico","Contraseña","Grupo");
$contenido_array=array($edit_entradas_ldap[0]['uid'][0],$edit_entradas_ldap[0]['uidnumber'][0],$edit_entradas_ldap[0]['givenname'][0],$edit_entradas_ldap[0]['sn'][0],$edit_entradas_ldap[0]['cn'][0],$edit_entradas_ldap[0]['mail'][0],preg_replace("/[A-Za-z0-9]/","*",$edit_entradas_ldap[0]['userpassword'][0]),$ldap_gid_flip[$edit_entradas_ldap[0]['gidnumber'][0]]);
$edit_array=array(0,0,1,1,0,1,0,0);
$num_array=count($edit_array)-1;
$quien=str_replace(",","__@_@_@__",$edit_entradas_ldap[0]['dn']);
$quien=str_replace("=","__%_%_%__",$quien);

echo "<table>";

for ($i = 0; $i <= $num_array; $i++) {
asistente_ajax($objeto_array[$i],$etiqueta_array[$i],$contenido_array[$i],$edit_array[$i],$quien);
}

echo "</table>";

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

include "themes/$app_theme/footer.php";

?>
