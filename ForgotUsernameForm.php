<?php
include "config.php";
include "themes/$app_theme/header.php";
?>

<h2><?= _("Recordar Nombre de Usuario") ?></h2>

<p><?= _("Por favor introduce a continuación la dirección de correo electrónico que utilizaste para registrar tu usuario.") ?></p>

<form method="post" action="ForgotUsernameDo.php">
    <table>
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
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
include "themes/$app_theme/footer.php";
?>