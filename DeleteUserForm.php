<?php
include "config.php";
include "themes/$app_theme/header.php";
?>

<h2><?= _("Eliminación de Usuario") ?></h2>

<p><?= _("Por favor complete el siguiente formulario para borrar DEFINITIVAMENTE tu usuario de la ") . $app_name . "." ?></p>

<form method="post" action="DeleteUserDo.php">
    <table>
        <tr>
            <td class="px160">
                <?= _("Nombre de usuario") ?>
            </td>
            <td class="px120">
                <?= _("p. ej., emeteri0") ?>
            </td>
            <td class="px640">
                <span id="uid_js">
                    <input type="text" name="uid" id="uid" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("No puedes dejar éste campo en blanco.") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("Excediste el número máximo de 60 Caracteres.") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("Correo electrónico") ?>
            </td>
            <td class="px120">
                <?= _("p. ej., eme@terio.org.ve") ?>
            </td>
            <td class="px640">
                <span id="mail_js">
                    <input type="text" name="mail" id="mail" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("No puedes dejar éste campo en blanco.") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("Excediste el número máximo de 60 Caracteres.") ?>
                    </span>
                    <span class="textfieldInvalidFormatMsg">
                        <?= _("Formato Inválido.") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("Contraseña") ?>
            </td>
            <td class="px120">
                <?= _("al menos ocho caracteres") ?>
            </td>
            <td class="px640">
                <span id="userPassword_js">
                    <input type="password" name="userPassword" id="userPassword" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("No puedes dejar éste campo en blanco.") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("Excediste el número máximo de 20 Caracteres.") ?>
                    </span>
                    <span class="textfieldMinCharsMsg">
                        <?= _("La contraseña debe tener al menos 8 Caracteres.") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("Verificación") ?>
            </td>
            <td class="px120">
                <img alt="captcha" src="captcha.php" border="0">
            </td>
            <td class="px640">
                <span id="image_captcha_js">
                    <input name="image_captcha" id="image_captcha" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("No puedes dejar éste campo en blanco.") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("Excediste el número máximo de 8 Caracteres.") ?>
                    </span>
                </span>
            </td>
        </tr>
    </table>
    <input type="submit" value="<?= _("ENVIAR") ?>" class="boton" />
</form>
<script type="text/javascript">
    var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:60});
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:20, minChars:8});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
include "themes/$app_theme/footer.php";
?>