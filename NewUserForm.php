<?php
include_once "config.php";
include_once "Locale.php";
include_once "themes/$app_theme/header.php";
?>

<h2><?= _("REQUESTNEWUSERACCOUNT:TITLE") ?></h2>

<p><?= _("REQUESTNEWUSERACCOUNT:GREETINGS") . $app_name . _("REQUESTNEWUSERACCOUNT:INTRO") ?></p>

<form method="post" action="NewUserMail.php">
    <table>
        <tr>
            <td class="px160">
                <?= _("FIRSTNAME:FORM") ?>
            </td>
            <td class="px120">
                <?= _("FIRSTNAME:EXAMPLE") ?>
            </td>
            <td class="px640">
                <span id="givenName_js">
                    <input type="text" name="givenName" id="givenName" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN60") ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="px160">
                <?= _("LASTNAME:FORM") ?>
            </td>
            <td class="px120">
                <?= _("LASTNAME:EXAMPLE") ?>
            </td>
            <td class="px640">
                <span id="sn_js">
                    <input type="text" name="sn" id="sn" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldMaxCharsMsg">
                        <?= _("LONGERTHAN60") ?>
                    </span>
                </span>
            </td>
        </tr>
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
                <?= _("PASSWORD") ?>
            </td>
            <td class="px120">
                <?= _("ATLEAST8CHARS") ?>
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
                <?= _("PASSWORDREPEAT") ?>
            </td>
            <td class="px120">
                <?= _("ATLEAST8CHARS") ?>
            </td>
            <td class="px640">
                <span id="userPasswordBis_js">
                    <input type="password" name="userPasswordBis" id="userPasswordBis" class="input5" />
                    <span class="textfieldRequiredMsg">
                        <?= _("EMPTY:WARNING") ?>
                    </span>
                    <span class="textfieldInvalidFormatMsg">
                        <?= _("PASSWORD:DIFFERENT") ?>
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
    var passwordIgual = function(value, options){
                            var other_value = document.getElementById("userPassword").value;
                            if (value != other_value){ return false; }
                            return true
                            }
    var givenName_var = new Spry.Widget.ValidationTextField("givenName_js", "none", {validateOn:["blur"], maxChars:60});
    var sn_var = new Spry.Widget.ValidationTextField("sn_js", "none", {validateOn:["blur"], maxChars:60});
    var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:30, minChars:3});
    var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
    var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:30, minChars:8});
    var userPasswordBis_var = new Spry.Widget.ValidationTextField("userPasswordBis_js", "custom", {validation: passwordIgual, validateOn:["blur"], maxChars:30, minChars:8});
    var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>
<?php
include_once "themes/$app_theme/footer.php";
?>