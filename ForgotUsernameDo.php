<?php 

$allowed_ops=array("mail","image_captcha");
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "Functions.php";

?>

<h2>Resultados de la Búsqueda</h2>

<?php

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

//Consultamos si ya existe la cuenta en el LDAP
$olvido_limitar = array("uidNumber","uid","cn","gidNumber");
$olvido_filtro_buscar = "(mail=".$mail.")";
$olvido_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $olvido_filtro_buscar, $olvido_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$olvido_entradas_ldap = ldap_get_entries($ldapc, $olvido_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error() . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

$total_usuarios=$olvido_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

if($total_usuarios==0){

?>

<div class="error">
No existe ninguna cuenta asociada a ese correo.
<br /><br />
<a href="javascript:history.back(1);">Volver</a>
</div>

<?php

}else{
	
?>
<p>Las siguientes cuentas están asociadas al correo "<strong><?php echo $mail; ?></strong>":</p>
<?php

echo "<table>";
echo "<tr><td class=\"px70\"><strong>ID</strong></td><td class=\"px300\"><strong>Nombre de Usuario</strong></td><td class=\"px360\"><strong>Nombre Real</strong></td><td class=\"px70\"><strong>Grupo</strong></td></tr>";

for ($i = 0; $i <= $total_usuarios_1; $i++) {
echo "<td class=\"px70\">".$olvido_entradas_ldap[$i]['uidnumber'][0]."</td>";
echo "<td class=\"px300\">".$olvido_entradas_ldap[$i]['uid'][0]."</td>";
echo "<td class=\"px360\">".$olvido_entradas_ldap[$i]['cn'][0]."</td>";
echo "<td class=\"px70\">".$ldap_gid_flip[$olvido_entradas_ldap[$i]['gidnumber'][0]]."</td></tr>";
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
