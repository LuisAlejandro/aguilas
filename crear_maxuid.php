<?php

$new_limitar = array("uidNumber");
$new_filtro_buscar="(uid=maxUID)";
$new_verificar_ldap = ldap_search($ldapc, $ldap_buscar, $new_filtro_buscar, $new_limitar) or die ('<div class="error">Hubo un error en la buśqueda con el LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');
$new_entradas_ldap = ldap_get_entries($ldapc, $new_verificar_ldap) or die ('<div class="error">Hubo un error retirando los resultados del LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

if($new_entradas_ldap['count']==0){

//Especificamos el dn del nuevo usuario
$ldap_nuevo="uid=maxUID,".$ldap_nuevo;
//Llenamos el array $in con los datos del nuevo usuario
$in['sn'] = "maxUID";
$in['cn'] = "maxUID";
$in['uid'] = "maxUID";
$in['uidNumber'] = "1";
$in['objectClass'][0] = "extensibleObject";
$in['objectClass'][1] = "person";
$in['objectClass'][2] = "top";

//Hacemos el query con los datos
$r = ldap_add($ldapc,$ldap_nuevo,$in) or die ('<div class="error">Hubo un error insertando entradas al LDAP: ' . ldap_error($ldapc) . '.<br /><br /><a href="javascript:history.back(1);">Atrás</a></div>');

}

?>
