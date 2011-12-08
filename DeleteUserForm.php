<?php
include "config.php";
include "themes/$app_theme/header.php";
?>

<h2><?= _("##DELETEUSER:TITLE##") ?></h2>

<p><?= _("##DELETEUSER:GREETINGS##") . $app_name . "." ?></p>

<form method="post" action="DeleteUserDo.php">
    <table>
        <tr>
            <td class="px160">
                <?= _("##USERNAME##") ?>
            </td>
            <td class="px120">
                <?= _("##USER:EXAMPLE##") ?>
            </td>
            <td class="px640">
                <span id="uid_js">
                    <input type="text" name="uid" id="uid" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("##EMPTY:WARNING##") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("##LONGERTHAN30##") ?>
                    </span>
                    <span class="textfieldMinCharsMsg">
                        <?= _("##ATLEAST3##") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("##EMAIL##") ?>
            </td>
            <td class="px120">
                <?= _("##EMAIL:EXAMPLE##") ?>
            </td>
            <td class="px640">
                <span id="mail_js">
                    <input type="text" name="mail" id="mail" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("##EMPTY:WARNING##") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("##LONGERTHAN60##") ?>
                    </span>
                    <span class="textfieldInvalidFormatMsg">
                        <?= _("##INVALIDFORMAT##") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("##PASSWORD##") ?>
            </td>
            <td class="px120">
                <?= _("##ATLEAST8CHARS##") ?>
            </td>
            <td class="px640">
                <span id="userPassword_js">
                    <input type="password" name="userPassword" id="userPassword" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("##EMPTY:WARNING##") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("##LONGERTHAN30##") ?>
                    </span>
                    <span class="textfieldMinCharsMsg">
                        <?= _("##ATLEAST8##") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("##CAPTCHA##") ?>
            </td>
            <td class="px120">
                <img alt="captcha" src="captcha.php" border="0">
            </td>
            <td class="px640">
                <span id="image_captcha_js">
                    <input name="image_captcha" id="image_captcha" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("##EMPTY:WARNING##") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("##LONGERTHAN8##") ?>
                    </span>
                </span>
            </td>
        </tr>
    </table>
    <input type="submit" value="<?= _("##SEND##") ?>" class="boton" />
</form>
<script type="text/javascript">
    var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:30, minChars:3});
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:30, minChars:8});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
include "themes/$app_theme/footer.php";
?>