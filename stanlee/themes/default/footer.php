<div class="visualClear"></div>
</div>
<div id="portal-footer">
    <div id="footer">
        <a href="NewUserForm.php">
            <?= _("New User") ?>
        </a>
        <a href="ChangePasswordForm.php">
            <?= _("Change Password") ?>
        </a>
        <a href="ResetPasswordForm.php">
            <?= _("Reset Password") ?>
        </a>
        <a href="ForgotUsernameForm.php">
            <?= _("Remind User") ?>
        </a>
        <a href="DeleteUserForm.php">
            <?= _("Delete User") ?>
        </a>
        <a href="EditProfileForm.php">
            <?= _("Edit Profile") ?>
        </a>
        <a href="Browse.php">
            <?= _("Browse Users") ?>
        </a>
    </div>
    
    <div>
        <?= $app_name . " " . _(" is powered by ") ?>
        <strong><a href="http://code.google.com/p/stanlee/">STANLEE</a></strong>.
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

$ExpireTime = 60*60*24*365;
header('Content-type: text/html; charset=utf-8');
header('Expires: '.gmdate('D, d M Y H:i:s', time()-$ExpireTime).' GMT');

?>
