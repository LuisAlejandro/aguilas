<?php
include "config.php";
include "themes/$app_theme/header.php";
?>

<h2><?= _("Cambiar Contraseña") ?></h2>

<p><?= _("Por favor completa el siguiente formulario para cambiar tu contraseña.") ?></p>

<form method="post" action="ChangePasswordDo.php">
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
                <?= _("Contraseña Actual") ?>
            </td>
            <td class="px120">
                <?= _("introduce tu contraseña actual") ?>
            </td>
            <td class="px640">
                <span id="userPasswordOld_js">
                    <input type="password" name="userPasswordOld" id="userPasswordOld" class="input5" />
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
                <?= _("Contraseña Nueva") ?>
            </td>
            <td class="px120">
                <?= _("introduce la nueva contraseña") ?>
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
                <?= _("Contraseña Nueva (repítela)") ?>
            </td>
            <td class="px120">
                <?= _("repite la nueva contraseña") ?>
            </td>
            <td class="px640">
                <span id="userPasswordBis_js">
                    <input type="password" name="userPasswordBis" id="userPasswordBis" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("No puedes dejar éste campo en blanco.") ?>
                    </span>
                    <span class="textfieldInvalidFormatMsg">
                        <?= _("Las contraseñas no coinciden.") ?>
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
                <img alt="captcha" src="captcha.php" border="0" />
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
    var passwordIgual = function(value, options){
                            var other_value = document.getElementById("userPassword").value;
                            if (value != other_value){ return false; }
                            return true
                            }
    var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:60});
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var userPasswordOld_var = new Spry.Widget.ValidationTextField("userPasswordOld_js", "none", {validateOn:["blur"], maxChars:20, minChars:8});
    var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:20, minChars:8});
    var userPasswordBis_var = new Spry.Widget.ValidationTextField("userPasswordBis_js", "custom", {validation: passwordIgual, validateOn:["blur"], maxChars:20, minChars:8});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
include "themes/$app_theme/footer.php";
?>