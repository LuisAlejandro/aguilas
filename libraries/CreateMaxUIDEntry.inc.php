<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die(_("FORM:ERROR"));
}

require_once "../setup/config.php";
require_once "../libraries/Locale.inc.php";
require_once "../libraries/LDAPConnection.inc.php";
require_once "../libraries/Functions.inc.php";

// We construct the DN of our new entry
$newdn = $maxuiddn;

// We fill in our attribute array $in for the new entry
$in['sn'] = "maxUID";
$in['cn'] = "maxUID";
$in['uid'] = "maxUID";
$in['uidNumber'] = $maxuidstart;
$in['objectClass'][0] = "extensibleObject";
$in['objectClass'][1] = "person";
$in['objectClass'][2] = "top";

// Adding new entry
$add = AssistedLDAPAdd($ldapc, $newdn, $in);

?>