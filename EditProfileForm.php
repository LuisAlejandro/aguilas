<?php
$allowed_ops = "EditProfileForm";
require_once "./setup/config.php";
require_once "./libraries/Locale.inc.php";
require_once "./themes/$app_theme/header.php";
?>

<h2><?= _("EDITUSERPROFILE") ?></h2>

<p><?= _("EDITUSERPROFILE:GREETINGS") . $app_name . "." ?></p>

<form method="post" action="EditProfileDo.php">
    <table>
        <tr>
            <td class="px160">
                <?= _("USERNAME") ?>
            </td>
            <td class="px120">
                <?= _("USER:EXAMPLE") ?>
            </td>
            <td class="px640">
                <span id="uid_js">
                    <input type="text" name="uid" id="uid" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN30") ?>
                    </span>
                    <span class="textfieldMinCharsMsg">
                        <?= _("ATLEAST3") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("PASSWORD") ?>
            </td>
            <td class="px120">
                <?= _("ENTERYOURPASSWORD:FORM") ?>
            </td>
            <td class="px640">
                <span id="userPassword_js">
                    <input type="password" name="userPassword" id="userPassword" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN30") ?>
                    </span>
                    <span class="textfieldMinCharsMsg">
                        <?= _("ATLEAST8") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                Verificación") ?>
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
    var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:30, minChars:3});
    var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:30, minChars:8});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
require_once "./themes/$app_theme/footer.php";
?>