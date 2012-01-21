<?php 

$allowed_ops = array("searchInput");

require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
require_once "./libraries/Parameters.inc.php";
require_once "./libraries/LDAPConnection.inc.php";
require_once "./libraries/Functions.inc.php";

?>

<h2><?= _("Search Results") ?></h2>

<?php

// USER INPUT VALIDATION ------------------------------------------------------- 
// The $searchInput was not set
if(!isset($searchInput)){

    VariableNotSet();

// The search entry was empty
}elseif($searchInput==''){

    EmptyVariable();

// Invalid characters on the search entry
}elseif(preg_match("/^[A-Za-záäéëíïóöúüñÁÄÉËÍÏÓÖÚÜÑ_\s?]+$/", $searchInput)==0){

    InvalidSearch();

}else{

    // VALIDATION PASSED -----------------------------------------------------------
    // We stablish what attributes are going to be retrieved from each entry
    $search_limit = array("uidNumber","uid","cn","gidNumber");

    // The filter string to search through LDAP
    $search_string = "(|(uid=*".$searchInput."*)(cn=*".$searchInput."*))";

    // The attribute the array of entries is going to be sorted by
    $sort_string = 'cn';

    // Searching ...
    $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

    // How much did we get?
    $result_count = $search_entries['count'];

    echo $result_count . _(" Users found matching the given criteria.");

    // Parsing the user table with the result entries
    ParseUserTable($search_entries, $result_count);

}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

require_once "./themes/$app_theme/footer.php";

?>