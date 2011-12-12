<?php

/******************************************************************************
 *                       DATABASE MANIPULATION FUNCTIONS                      *
 ******************************************************************************/

function AssistedLDAPAdd($ldapc, $newdn, $in) {
    $r_add = ldap_add($ldapc, $newdn, $in)
            or die(
                    '<div class="error">'
                    . _("Hubo un error agregando entradas del LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_add);
}
    
function AssistedLDAPModify($ldapc, $moddn, $in) {
    $r_mod = ldap_modify($ldapc, $moddn, $in)
            or die(
                    '<div class="error">'
                    . _("Hubo un error modificando entradas del LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_mod);
}
    
function AssistedLDAPDelete($ldapc, $dn) {
    $r_del = ldap_delete($ldapc, $dn)
            or die(
                    '<div class="error">'
                    . _("Hubo un error eliminando entradas del LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($r_del);
}

function AssistedLDAPClose($ldapc) {
    $ldapx = ldap_close($ldapc)
            or die(
                    '<div class="error">'
                    . _("Hubo un error cerrando la conexión con el LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($ldapx);
}

function AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string) {

    // Searching...
    $search_result = ldap_search($ldapc, $ldap_base, $search_string, $search_limit)
            or die(
                    '<div class="error">'
                    . _("Hubo un error en la buśqueda con el LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );

    // Sorting the result by cn
    $search_sort = ldap_sort($ldapc, $search_result, $sort_string)
            or die(
                    '<div class="error">'
                    . _("Hubo un error ordenando los resultados del LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );

    // Getting the all the entries
    $search_entries = ldap_get_entries($ldapc, $search_result)
            or die(
                    '<div class="error">'
                    . _("Hubo un error retirando los resultados del LDAP: ")
                    . ldap_error($ldapc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($search_entries);
}

function AssistedMYSQLQuery($query) {
    $result = mysql_query($query)
            or die(
                    '<div class="error">'
                    . _("Hubo un error en la consulta a la Base de Datos MYSQL: ")
                    . mysql_error($query)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($result);
}

function AssistedMYSQLClose($mysqlc) {
    $mysqlx = mysql_close($mysqlc)
            or die(
                    '<div class="error">'
                    . _("Hubo un error cerrando la conexión con la Base de Datos MYSQL: ")
                    . mysql_error($mysqlc)
                    . '.<br /><br /><a href="javascript:history.back(1);">'
                    . _("Atrás")
                    . '</a></div>'
                    . file_get_contents("themes/$app_theme/footer.php")
    );
    return($mysqlx);
}


/******************************************************************************
 *                           COMMUNICATION FUNCTIONS                          *
 ******************************************************************************/

function AssistedEMail($what, $where) {
    // What are the headers?
    $headers = "From: " . $app_mail . "\nContent-Type: text/html; charset=utf-8";

    // What's the message?
    switch ($what) {
        case "ChangePasswordDo":
            $subject = _("Cambio de Contraseña en ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("Hola! Tu contraseña en ") . $app_name . _(" ha sido cambiada.")
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordMail":
            $subject = _("Petición de Nueva Contraseña en ") . $app_name;
            $go_link = "http://" . $app_url . "/ResetPasswordDo.php"
                    . "?mail=" . $mail
                    . "&uid=" . $uid
                    . "&token=" . $token;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("Has recibido éste correo porque alguien solicitó una nueva contraseña para el usuario ")
                    . '<strong>' . $uid . '</strong>'
                    . _(" en ") . $app_name . '.'
                    . '</p><p>'
                    . _("Haz click en el siguiente enlace para confirmar tu petición.")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">CONFIRMAR</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "ResetPasswordDo":
            $subject = _("Nueva Contraseña en ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>'
                    . _("Hola, tu nueva contraseña es: ")
                    . '</p>'
                    . '<p><strong>' . $genPassword . '</strong></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "NewUserMail":
        case "ResendMailDo":
            $subject = _("Activación de Nuevo Usuario en ") . $app_name;
            $go_link = "http://" . $app_url . "/NewUserDo.php"
                    . "?mail=" . $mail
                    . "&uid=" . $uid
                    . "&token=" . $token;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Hola, ") . '<strong>' . $givenName . '</strong>.</p>'
                    . '<p>'
                    . _("Has recibido éste correo electrónico porque hiciste una petición de Nuevo Usuario en ")
                    . $app_name . '.'
                    . '</p><p>'
                    . _("Haz click en el siguiente enlace para confirmar tu petición.")
                    . '</p>'
                    . '<p><a href="' . $go_link . '">CONFIRMAR</a></p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;

        case "DeleteUserDo":
            $subject = _("Usuario Eliminado en ") . $app_name;
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
                    . '<HTML>'
                    . '<HEAD>'
                    . '<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">'
                    . '<TITLE>' . $subject . '</TITLE>'
                    . '<META NAME="GENERATOR" CONTENT="AGUILAS">'
                    . '<META NAME="AUTHOR" CONTENT="AGUILAS">'
                    . '</HEAD>'
                    . '<BODY LANG="' . $app_locale . '" DIR="LTR">'
                    . '<p>' . _("Estimado usuario ") . '"<strong>' . $uid . '</strong>".</p>'
                    . '<p>'
                    . _("Tu cuenta ha sido eliminada satisfactoriamente de ")
                    . $app_name . '.'
                    . '</p>'
                    . '<br /><br />'
                    . '<p>' . $app_operator . '</p>'
                    . '</BODY>'
                    . '</HTML>';
            break;
    }
    $send = mail($where, $subject, $body, $headers);
    return ($send);
}

/******************************************************************************
 *                           EVENT LOGGING FUNCTIONS                          *
 ******************************************************************************/

function WriteLog($data, $log_file, $time_today) {
    $log_location = "/var/log/aguilas/" . $log_file . ".log";
    switch ($log_file) {
        case "ChangePasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Cambiar Contraseña) a ")
                    . $data['mail'] . " (uid: " . $data['uid'] . ").\n";
            break;
        case "ResetPasswordMail":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de confirmación (Generar Contraseña) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "ResetPasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Generar Contraseña) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "ResendMailDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha re-enviado un correo de confirmación (Nuevo Usuario) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "NewUserMail":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de confirmación (Nuevo Usuario) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid']
                    . "; token: " . $data['token'] . ").\n";
            break;
        case "DeleteUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Eliminar Usuario) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid'] . ").\n";
            break;
        case "NewUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Nuevo Usuario) a ")
                    . $data['mail']
                    . " (uid: " . $data['uid'] . ").\n";
            break;
    }
    $log_write = file_put_contents($log_location, $log_string, FILE_APPEND | LOCK_EX);
    return($log_write);
}

/******************************************************************************
 *                           VALIDATION FUNCTIONS                             *
 ******************************************************************************/

function EncodePassword($password, $type) {
    switch ($type) {
        case "PLAIN":
            $hash = $password;
            break;
        case "SSHA":
            for ($i = 1; $i <= 10; $i++) {
                $salt .= substr('0123456789abcdef', rand(0, 15), 1);
            }
            $hash = "{SSHA}" . base64_encode(pack("H*", sha1($password . $salt)) . $salt);
            break;
        case "MD5":
            $hash = "{MD5}" . base64_encode(pack("H*", md5($password)));
            break;
    }
    return $hash;
}
        
function DecodePassword($password, $hash, $type) {
    switch ($type) {
        case "PLAIN":
            $depass = $hash;
            $encpass = $password;
            break;
        case "SSHA":
            $decode = base64_decode(str_replace("{SSHA}", "", $hash));
            $desalt = substr($decode, 20);
            $depass = substr($decode, 0, 20);
            $encpass = pack("H*", sha1($password . $desalt));
            break;
        case "MD5":
            $decode = base64_decode(str_replace("{MD5}", "", $hash));
            $depass = $decode;
            $encpass = pack("H*", md5($password));
            break;
    }
    
    if ( $encpass == $depass ) {
        return true;
    } else {
        return false;
    }
}

function InitCaptcha() {
    // CAPTCHA -----------------------------------------------------------------
    // Starting session (cookies)
    session_start();
    // We get the hash from the cookie
    // If it's not there, then the cookie expired
    if (isset($_SESSION['captcha'])) {
        $session_captcha = $_SESSION['captcha'];
    }

    if (isset($image_captcha)){
        // Let's MD5 the user entry
        $image_captcha = md5($image_captcha);
    } else {
        WrongCaptcha();
    }
}

function VariableNotSet() {
    ?>
    <div class="error">
        <?= _("Hubo un error en el llenado del Formulario. Por favor vuelve atrás y verifica que hayas rellenado todos los datos.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function EmptyVariable() {
    ?>
    <div class="error">
        <?= _("Hubo un error en el llenado del Formulario. Por favor vuelve atrás y verifica que hayas rellenado todos los datos.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidSearch() {
    ?>
    <div class="error">
        <?= _("Tu búsqueda contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ, guión inferior (_) y palabras acentuadas o con diéresis).") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidEMail() {
    ?>
    <div class="error">
        <?= _("El Correo Electrónico proporcionado no es válido. Verifica si lo has escrito correctamente.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidToken() {
    ?>
    <div class="error">
        <?= _("El token de confirmación es inválido. Por favor realiza la solicitud de nuevo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidUsername() {
    ?>
    <div class="error">
        <?= _("El nombre de usuario es inválido. Sólo se permiten letras (mayúsculas y minúsculas), números, guiones (-) y guiones bajos (_).") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidPassword() {
    ?>
    <div class="error">
        <?= _("La contraseña contiene caracteres inválidos. Sólo se permiten letras (mayúsculas y minúsculas), números y los siguientes símbolos: . ! @ # $ % ^ & + = - _") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function InvalidOldPassword() {
    ?>
    <div class="error">
        <?= _("La contraseña antigua contiene caracteres inválidos, lo cual es muy extraño. Utiliza el formulario de generar nueva contraseña para poder cambiarla.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function DifferentPasswords() {
    ?>
    <div class="error">
        <?= _("Las contraseñas no coinciden.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function WrongPasswordLength() {
    ?>
    <div class="error">
        <?= _("La longitud de la contraseña debe ser de 8 caracteres mínimo y 30 caracteres máximo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function WrongUIDLength() {
    ?>
    <div class="error">
        <?= _("La longitud del nombre de usuario debe ser de 3 caracteres mínimo y 30 caracteres máximo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function Wrong1NameLength() {
    ?>
    <div class="error">
        <?= _("Tu nombre tiene una longitud mayor a 60 caracteres.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function Wrong2NameLength() {
    ?>
    <div class="error">
        <?= _("Tu apellido tiene una longitud mayor a 60 caracteres.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function WrongOldPasswordLength() {
    ?>
    <div class="error">
        <?= _("La longitud de la contraseña antigua es muy larga o muy corta, lo cual es muy extraño. Utiliza el formulario de generar nueva contraseña para poder cambiarla.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function Invalid1Name() {
    ?>
    <div class="error">
        <?= _("Tu nombre contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function Invalid2Name() {
    ?>
    <div class="error">
        <?= _("Tu apellido contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).") ?>
        <br /><br />
        <a href="javascript:history.back(1);">Atrás</a>
    </div>
    <?php
}

function UserExists() {
    ?>
    <div class="error">
        <?= _("El nombre de usuario escogido ya existe.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function UsedEMail() {
    ?>	
    <div class="error">
        <?= _("El correo indicado ya está asociado a un usuario.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

/******************************************************************************
 *                        RESULT MESSAGES FUNCTIONS                           *
 ******************************************************************************/

function NoRequests() {
    ?>
    <div class="error">
        <?= _("No se han encontrado peticiones que coincidan con los datos proporcionados.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function MultipleResults() {
    ?>
    <div class="error">
        <?= _("Existe una inconsistencia en la Base de Datos. Existen dos o más cuentas que comparten algunos de los datos de usuario (nombre, correo, etc..), lo cual genera errores e imprecisiones en el sistema. Este error ha sido informado a los administradores para que realicen una correción manual.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function NoResults() {
?>
    <div class="error">
        <?= _("La información proporcionada no coincide con alguna cuenta existente. Por favor revisa los datos introducidos.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
<?php
}

function Fail($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $fail_string = _("Ocurrió un error cambiando tu contraseña. Por favor intenta de nuevo más tarde.");
            break;
        case "ResetPasswordMail":
            $fail_string = _("Ocurrió un error enviando el correo de confirmación. Por favor intenta de nuevo más tarde.");
            break;
        case "ResetPasswordDo":
            $fail_string = _("Ocurrió un error generando tu nueva contraseña. Por favor intenta de nuevo más tarde.");
            break;
        case "DeleteUserDo":
            $fail_string = _("Ocurrió un error eliminando tu cuenta. Por favor intenta de nuevo más tarde.");
            break;
        case "NewUserDo":
            $fail_string = _("Ocurrió un error creando tu cuenta. Por favor intenta de nuevo más tarde.");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $fail_string = _("Ocurrió un error enviando el correo de confirmación. Por favor intenta de nuevo más tarde.");
            break;
    }
    ?>
    <div class="error">
        <?= $fail_string ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
    <?php
}

function Success($at) {
    switch ($at) {
        case "ChangePasswordDo":
            $success_string = _("¡Éxito! Tu contraseña ha sido cambiada.");
            break;
        case "ResetPasswordMail":
            $success_string = _("La solicitud de una nueva contraseña ha sido procesada correctamente. Revisa tu correo electrónico para finalizar el proceso.");
            break;
        case "ResetPasswordDo":
            $success_string = _("¡Éxito! Hemos generado una nueva contraseña para ti. Revisa tu correo electrónico para encontrar más información.");
            break;
        case "DeleteUserDo":
            $success_string = _("Tu cuenta ha sido eliminada satisfactoriamente.");
            break;
        case "NewUserDo":
            $success_string = _("¡Éxito! Tu cuenta ha sido creada satisfactoriamente.");
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $success_string = _("La solicitud de la nueva cuenta ha sido procesada correctamente. Revisa tu correo electrónico para finalizar el proceso.");
            break;
    }
    ?>
    <div class="exito">
        <?= $success_string ?>
        <br /><br />
        <a href="index.php"><?= _("Portada") ?></a>
    </div>
    <?php
}

// HTML writer Library
    function ParseUserTable($search_entries, $result_count) {
        $result_count_1 = $result_count - 1;
        ?>
    <table>
        <tr>
            <td class="px70">
                <strong><?= _("ID") ?></strong>
            </td>
            <td class="px300">
                <strong><?= _("Nombre de Usuario") ?></strong>
            </td>
            <td class="px360">
                <strong><?= _("Nombre Real") ?></strong>
            </td>
            <td class="px70">
                <strong><?= _("Grupo") ?></strong>
            </td>
        </tr>
    <?php
// Parsing the results nice and neat
    for ($i = 0; $i <= $result_count_1; $i++) {
        echo '<tr><td class="px70">' . $search_entries[$i]['uidnumber'][0] . '</td>';
        echo '<td class="px300">' . $search_entries[$i]['uid'][0] . '</td>';
        echo '<td class="px360">' . $search_entries[$i]['cn'][0] . '</td>';
        echo '<td class="px70">' . $ldap_gid_flip[$search_entries[$i]['gidnumber'][0]] . '</td></tr>';
    }
    ?>
    </table>
        <?php
    }

function AJAXAssistant($objects, $tags, $contents, $edit, $who) {
        ?>
    <tr>
        <td class="px160">
            <?= $tags ?>
        </td>
        <td class="px120">
            <?php
            
            if ($edit) {
                echo _("pulse sobre el campo para editarlo");
            } else {
                echo _("este campo no puede editarse");
            }
            
            ?>
        </td>
        <td class="px640">
            <div>
                <?php

                if ($edit) {
                    echo '<table class="infoBox" cellSpacing="2" cellPadding="3">';
                } else {
                    echo '<table class="infoBox_null">';
                }

                ?>
                    <tr valign="middle">
                        <td id="<?= $objects ?>_rg" <?php if ($edit) { ?>onmouseover="flashRow(this);" onclick="changeAjax('<?= $objects ?>');" onmouseout="unFlashRow(this);"<?php } ?>>
                            <div class="superBigSize" id="<?= $objects ?>_rg_display_section">
                                <?= $contents ?>
                            </div>
                        </td>
                            <?php if ($edit) { ?>
                            <td id="<?= $objects ?>_hv">
                                <div id="<?= $objects ?>_hv_editing_section">
                                    <input class="superBigSize editMode" id="<?= $objects ?>" name="<?= $objects ?>" value="<?= $contents ?>" <?php if ($objects == "givenName" || $objects == "sn") { ?>onkeyup="update_cn();"<?php } ?> />&nbsp;
                                    <input class="AjaxButton" onclick="sendAjax('<?= $objects ?>','<?= $who ?>');" type="button" value="<?= _("Guardar") ?>" />&nbsp;
                                    <input class="AjaxButton" onclick="cancelAjax('<?= $objects ?>');" type="button" value="<?= _("Cancelar") ?>" />
                                </div>
                                <span class="savingAjaxWithBackground" id="<?= $objects ?>_hv_saving_section">
                                    &#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;
                                </span>
                                <script type="text/javascript">
                                    document.getElementById('<?= $objects ?>_hv').style.display = 'none';
                                    document.getElementById('<?= $objects ?>_hv_saving_section').style.display = 'none';
                                </script>
                            </td>
    <?php } ?>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <?php
}



?>
