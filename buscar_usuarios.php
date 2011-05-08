<?php 

$op_permitidas=array("searchInput");
include "header.php";
include "get_post.php";
include "conectar_db_ldap.php";
include "functions.php";

?>

<h2>Resultados de la Búsqueda</h2>

<?php

// ---------- VALIDACIONES --------------------------------------------

if(!isset($searchInput)){

?>
	
<div class="error">
¡EPA! ¿Por donde estás tratando de entrar, muchachito o muchachita? Usa los canales regulares.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif($searchInput==''){

?>
	
<div class="error">
Hubo un error en el llenado del Formulario.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}elseif(preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ_\s?]+$/",$searchInput)==0){

?>

<div class="error">
Tu búsqueda contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ, guión inferior (_) y palabras acentuadas o con diéresis).
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{

//Hacemos la búsqueda en el LDAP
$search_limitar = array("uidNumber","uid","cn","gidNumber");
$search_filtro_buscar = "(|(uid=*".$searchInput."*)(cn=*".$searchInput."*))";
$search_verificar_ldap = ldap_search($ldapc, $ldap_base, $search_filtro_buscar, $search_limitar);
$search_entradas_ldap = ldap_get_entries($ldapc, $search_verificar_ldap);

$total_usuarios=$search_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

if($total_usuarios==0){
	
?>

<div class="error">
Ningún usuario encontrado con la descripción provista.
<br /><br />
<a href="javascript:history.back(1);">Atrás</a>
</div>

<?php

}else{
	
echo "<table>";
echo "<tr><td class=\"px70\"><strong>ID</strong></td><td class=\"px300\"><strong>Nombre de Usuario</strong></td><td class=\"px360\"><strong>Nombre Real</strong></td><td class=\"px70\"><strong>Grupo</strong></td></tr>";

for ($i = 0; $i <= $total_usuarios_1; $i++) {
echo "<tr><td class=\"px70\">".$search_entradas_ldap[$i]['uidnumber'][0]."</td>";
echo "<td class=\"px300\">".$search_entradas_ldap[$i]['uid'][0]."</td>";
echo "<td class=\"px360\">".$search_entradas_ldap[$i]['cn'][0]."</td>";
echo "<td class=\"px70\">".$ldap_gid_flip[$search_entradas_ldap[$i]['gidnumber'][0]]."</td></tr>";
}

echo "</table>";

}

}

ldap_close($ldapc);

include "footer.php";

?>
