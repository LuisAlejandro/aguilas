<?php
$allowed_ops = "ForgotUsernameForm";
require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
?>

<h2><?= _("REMINDUSERNAME:TITLE") ?></h2>

<p><?= _("REMINDUSERNAME:GREETINGS") ?></p>

<form method="post" action="ForgotUsernameDo.php">
    <table>
        <tr>
            <td class="px160">
                <?= _("EMAIL") ?>
            </td>
            <td class="px120">
                <?= _("EMAIL:EXAMPLE") ?>
            </td>
            <td class="px640">
                <span id="mail_js">
                    <input type="text" name="mail" id="mail" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN60") ?>
                    </span>
                    <span class="textfieldInvalidFormatMsg">
                        <?= _("INVALIDFORMAT") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("CAPTCHA") ?>
            </td>
            <td class="px120">
                <img alt="captcha" src="captcha.php" border="0">
            </td>
            <td class="px640">
                <span id="image_captcha_js">
                    <input name="image_captcha" id="image_captcha" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN8") ?>
                    </span>
                </span>
            </td>
        </tr>
    </table>
    <input type="submit" value="<?= _("SEND") ?>" class="boton" />
</form>
<script type="text/javascript">
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
require_once "./themes/$app_theme/footer.php";
?>