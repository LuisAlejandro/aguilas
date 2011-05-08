<?php 

include "header.php";
include "conectar_db_ldap.php";
include "functions.php";

?>

<h2>Usuarios de la Plataforma Colaborativa Canaima</h2>

<?php

//Hacemos la búsqueda en el LDAP
$listar_limitar = array("uidNumber","uid","cn","gidNumber");
$listar_filtro_buscar = "(uid=*)";
$listar_verificar_ldap = ldap_search($ldapc, $ldap_base, $listar_filtro_buscar, $listar_limitar);
$listar_entradas_ldap = ldap_get_entries($ldapc, $listar_verificar_ldap);

$total_usuarios=$listar_entradas_ldap['count'];
$total_usuarios_1=$total_usuarios-1;

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
