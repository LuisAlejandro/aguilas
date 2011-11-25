<?php 

include "header.php";
include "conectar_db_ldap.php";
include "functions.php";

?>

<h2>Usuarios de la Plataforma Colaborativa Canaima</h2>

<?php

$letters_array=Array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");

if(!isset($_GET['letter'])){
$letter="a";
}else{
$letter=$_GET['letter'];
}

echo "<table><tr>";
for ($j = 0; $j <= 25; $j++) {
echo "<td>&nbsp;&nbsp;<a href='listar_usuarios.php?letter=".$letters_array[$j]."'>".strtoupper($letters_array[$j])."</a>&nbsp;&nbsp;</td>";
}
echo "</tr></table>";

//Hacemos la búsqueda en el LDAP
$listar_limitar = array("uidNumber","uid","cn","gidNumber");
$listar_filtro_buscar="(&(cn=".$letter."*)(cn=".strtoupper($letter)."*)(!(objectClass=simpleSecurityObject))(!(uid=maxUID))(!(objectClass=posixGroup)))";
$listar_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $listar_filtro_buscar, $listar_limitar);
$listar_ordenar = ldap_sort($ldapc, $listar_verificar_ldap, 'cn');
$listar_entradas_ldap = ldap_get_entries($ldapc, $listar_verificar_ldap);

$total_usuarios=$listar_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;
echo $total_usuarios." Usuarios encontrados cuyo nombre real comienza por la letra ". strtoupper($letter);
echo "<table>";
echo "<tr><td class=\"px70\"><strong>ID</strong></td><td class=\"px300\"><strong>Nombre de Usuario</strong></td><td class=\"px360\"><strong>Nombre Real</strong></td><td class=\"px70\"><strong>Grupo</strong></td></tr>";

for ($i = 0; $i <= $total_usuarios_1; $i++) {
echo "<td class=\"px70\">".$listar_entradas_ldap[$i]['uidnumber'][0]."</td>";
echo "<td class=\"px300\">".$listar_entradas_ldap[$i]['uid'][0]."</td>";
echo "<td class=\"px360\">".$listar_entradas_ldap[$i]['cn'][0]."</td>";
echo "<td class=\"px70\">".$ldap_gid_flip[$listar_entradas_ldap[$i]['gidnumber'][0]]."</td></tr>";
}

echo "</table>";

ldap_close($ldapc);

include "footer.php";

?>
