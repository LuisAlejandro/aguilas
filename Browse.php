<?php 

$allowed_ops = array("letter");

include "config.php";
include "themes/$app_theme/header.php";
include "Parameters.php";
include "LDAPConnection.php";
include "Functions.php";

?>

<h2><?= _("Usuarios de ") . $app_name ?></h2>

<?php

// An array of letters to classify the search of users by the first letter
// of their cn
$letters_array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z");

// USER INPUT VALIDATION ------------------------------------------------------- 
// Do we have our letter?
if(!isset($letter)){
    
    $letter="a";
    
// Is it on our array?
}elseif(!in_array($letter, $letters_array)){
    
    ?>
    <div class="error">
        <?= _("Esa letra no existe.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
    
}else{

    // VALIDATION PASSED -------------------------------------------------------

    ?>

    <table>
        <tr>

    <?php

    // Creating the letter row with links
    for ($j = 0; $j <= 25; $j++) {
        echo "<td>&nbsp;&nbsp;<a href='Browse.php?letter=".$letters_array[$j]."'>".strtoupper($letters_array[$j])."</a>&nbsp;&nbsp;</td>";
        }

    ?>

        </tr>
    </table>

    <?php

    // We stablish what attributes are going to be retrieved from each entry
    $search_limit = array("uidNumber","uid","cn","gidNumber");

    // The filter string to search through LDAP
    $search_string = "(&(cn=".$letter."*)(cn=".strtoupper($letter)."*)(!(objectClass=simpleSecurityObject))(!(uid=maxUID))(!(objectClass=posixGroup)))";

    // The attribute the array of entries is going to be sorted by
    $sort_string = 'cn';

    // Searching ...
    $search_entries = AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string);

    // How much did we get?
    $result_count = $search_entries['count'];

    echo $result_count . _(" Usuarios encontrados cuyo nombre real comienza por la letra ") . strtoupper($letter);

    // Parsing the user table with the result entries
    ParseUserTable($search_entries, $result_count);

}

// Closing the connection
$ldapx = AssistedLDAPClose($ldapc);

include "themes/$app_theme/footer.php";

?>
