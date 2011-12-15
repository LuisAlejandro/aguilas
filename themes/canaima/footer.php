<div class="visualClear"></div>
</div>
<div id="portal-footer">
    <div id="footer">
        <a href="NewUserForm.php">
            <?= _("##NEWUSER##") ?>
        </a>
        <a href="ChangePasswordForm.php">
            <?= _("##CHANGEPASSWORD##") ?>
        </a>
        <a href="ResetPasswordForm.php">
            <?= _("##RESETPASSWORD##") ?>
        </a>
        <a href="ForgotUsernameForm.php">
            <?= _("##REMINDUSER##") ?>
        </a>
        <a href="DeleteUserForm.php">
            <?= _("##DELETEUSER##") ?>
        </a>
        <a href="EditProfileForm.php">
            <?= _("##EDITPROFILE##") ?>
        </a>
        <a href="Browse.php">
            <?= _("##BROWSEUSERS##") ?>
        </a>
    </div>
    
    <div>
        <?= $app_name . _("##POWEREDBY##") ?>
        <strong>AGUILAS</strong>.
    </div>
    
    <div>
        <strong>AGUILAS</strong>
        <?= _("##DEVELOPEDBY##") ?>
        <a href="http://www.huntingbears.com.ve/">
            Luis Alejandro Martínez Faneyth
        </a>.
    </div>
</div>
</div>
		
<script type="text/javascript">

    $(function() {

        $("#menu ul").lavaLamp({

            fx: "easeOutBack",
            speed: 700
        });
    });
    
</script>
</body>
</html>

<?php

header("Content-type: text/html; charset=utf-8");

?>
