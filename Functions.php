<?php

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
            $success_string = _("La solicitud de una nueva contraseña ha sido procesada correctamente. Revisa tu correo electrónico (") . $mail . _(") para finalizar el proceso.");
            break;
        case "ResetPasswordDo":
            $success_string = _("¡Éxito! Tu nueva contraseña es ") . '<strong>' . $newPassword . '</strong>.';
            break;
        case "NewUserMail":
        case "ResendMailDo":
            $success_string = _("La solicitud de la cuenta ") . '<strong>' . $uid . '</strong>' . _(" ha sido procesada correctamente. Revisa tu correo electrónico (") . $mail . _(") para finalizar el proceso.");
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

function AssistedEMail($what, $where) {
    $headers = "From: " . $app_mail . "\nContent-Type: text/html; charset=utf-8";

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
                    . '<p><strong>' . $newPassword . '</strong></p>'
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
}

function WriteLog($log_file, $time_today) {

    $log_location = __DIR__ . "/logs/" . $log_file . ".log";

    switch ($log_file) {
        case "ChangePasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Cambiar Contraseña) a ")
                    . $mail . " (uid: $uid).\n";
            break;

        case "ResetPasswordMail":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de confirmación (Generar Contraseña) a ")
                    . $mail . " (uid: $uid; token: $token).\n";
            break;

        case "ResetPasswordDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Generar Contraseña) a ")
                    . $mail . " (uid: $uid; token: $token).\n";
            break;

        case "ResendMailDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha re-enviado un correo de confirmación (Nuevo Usuario) a ")
                    . $mail . " (uid: $uid; token: $token).\n";
            break;

        case "NewUserMail":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de confirmación (Nuevo Usuario) a ")
                    . $mail . " (uid: $uid; token: $token).\n";
            break;

        case "DeleteUserDo":
            $log_string = "[" . $time_today . "]: "
                    . _("Se ha enviado un correo de éxito (Eliminar Usuario) a ")
                    . $mail . " (uid: $uid).\n";
            break;
    }

    $log_write = file_put_contents($log_location, $log_string, FILE_APPEND | LOCK_EX);
}

function VariableNotSet() {
    ?>
    <div class="error">
    <?= _("Hubo un error en el llenado del Formulario. Por favor vuelve atrás y verifica tus datos, no pueden existir campos vacíos.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
        <?php
    }

    function EmptyVariable() {
        ?>
    <div class="error">
    <?= _("Hubo un error en el llenado del Formulario.") ?>
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

function InitCaptcha() {
    // CAPTCHA ---------------------------------------------------------------------
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
    }else{
        WrongCaptcha();
    }
}
    
    function ExpiredCaptcha() {
        ?>
    <div class="error">
    <?= _("La página ha caducado. Vuelve atrás e intenta de nuevo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
        <?php
    }

    function WrongCaptcha() {
        ?>
    <div class="error">
    <?= _("Llenaste incorrectamente la imagen de verificación (CAPTCHA). Debes escribir exactamente lo que dice la imagen en el campo de texto.") ?>
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
    <?= _("El token de confirmación es inválido.") ?>
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
    <?= _("La longitud de la contraseña debe ser de 8 caracteres mínimo y 20 caracteres máximo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
        <?php
    }

    function NoRequests() {
        ?>
    <div class="error">
    <?= _("No se ha hecho petición de crear un usuario con esa dirección de correo. Intenta creando el usuario de nuevo.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
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

    function Invalid1Name() {
        ?>

        <div class="error">
            Tu nombre contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).
            <br /><br />
            <a href="javascript:history.back(1);">Atrás</a>
        </div>

        <?php
    }

    function Invalid2Name() {
                ?>

        <div class="error">
            Tu apellido contiene caracteres inválidos. Sólo se permiten letras (mayúsculas, minúsculas, ñ y palabras acentuadas o con diéresis).
            <br /><br />
            <a href="javascript:history.back(1);">Atrás</a>
        </div>

        <?php
    }

    function UserExists() {
        
    }

    function UserExists() {
        
    }

    function UserExists() {
        
    }

    function UserExists() {
        
    }

    function UserExists() {
        
    }

    function MultipleResults() {
        ?>
    <div class="error">
    <?= _("Existe una inconsistencia en la Base de Datos. Hay dos o más cuentas que comparten el mismo nombre de usuario y correo electrónico, lo cual es inapropiado. Para poder realizar el cambio de contraseña debes eliminar tu cuenta de usuario y crear una nueva.") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
        <?php
    }

    function NoResults() {
        ?>
    <div class="error">
    <?= _("La información proporcionada no coincide con alguna cuenta existente. ¿Escribiste bien los datos?") ?>
        <br /><br />
        <a href="javascript:history.back(1);"><?= _("Atrás") ?></a>
    </div>
        <?php
    }

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

    function AssistedLDAPModify($ldapc, $mod_dn, $in) {
        $r = ldap_modify($ldapc, $mod_dn, $in)
                or die(
                        '<div class="error">'
                        . _("Hubo un error modificando entradas del LDAP: ")
                        . ldap_error($ldapc)
                        . '.<br /><br /><a href="javascript:history.back(1);">'
                        . _("Atrás")
                        . '</a></div>'
                        . file_get_contents("themes/$app_theme/footer.php")
        );
        return($r);
    }
    
        function AssistedLDAPDelete($ldapc, $dn) {
        $r = ldap_delete($ldapc, $dn)
                or die(
                        '<div class="error">'
                        . _("Hubo un error eliminando entradas del LDAP: ")
                        . ldap_error($ldapc)
                        . '.<br /><br /><a href="javascript:history.back(1);">'
                        . _("Atrás")
                        . '</a></div>'
                        . file_get_contents("themes/$app_theme/footer.php")
        );
        return($r);
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

    function AssistedMYSQLQuery($query) {
        $result = mysql_query($query)
                or die(
                        '<div class="error">'
                        . _("Hubo un error insertando entradas en la tabla 'ResetPassword': ")
                        . ldap_error($ldapc)
                        . '.<br /><br /><a href="javascript:history.back(1);">'
                        . _("Atrás")
                        . '</a></div>'
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
        );
        return($mysqlx);
    }

    function AssistedLDAPSearch($ldapc, $ldap_base, $search_string, $search_limit, $sort_string) {

// We stablish what attributes are going to be retrieved from each entry
        $search_limit = array("uidNumber", "uid", "cn", "gidNumber");

// The filter string to search through LDAP
        $search_string = "(&(cn=" . $letter . "*)(cn=" . strtoupper($letter) . "*)(!(objectClass=simpleSecurityObject))(!(uid=maxUID))(!(objectClass=posixGroup)))";

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
        $search_sort = ldap_sort($ldapc, $search_result, 'cn')
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