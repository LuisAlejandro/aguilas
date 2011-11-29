<?php

// We stablish what attributes are going to be retrieved from each entry
$search_limit = array("uidNumber");

// The filter string to search through LDAP
$search_string = "(uid=maxUID)";

// The attribute the array of entries is going to be sorted by
$sort_string = 'cn';

// Searching ...
$search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

// How much did we get?
$result_count = $search_entries['count'];

if($result_count==0){

    // We construct the DN of our new entry
    $new_entry="uid=maxUID,".$ldap_base;

    // We fill in our attribute array $in for the new entry
    $in['sn'] = "maxUID";
    $in['cn'] = "maxUID";
    $in['uid'] = "maxUID";
    $in['uidNumber'] = "1";
    $in['objectClass'][0] = "extensibleObject";
    $in['objectClass'][1] = "person";
    $in['objectClass'][2] = "top";

    // Adding new entry
    $r = ldap_add($ldapc, $new_entry, $in)
            or die (
            '<div class="error">'
            . _("Hubo un error insertando la nueva entrada en el LDAP: ")
            . ldap_error($ldapc)
            . '.<br /><br /><a href="javascript:history.back(1);">'
            . _("Atrás")
            . '</a></div>'
            );

}

?>