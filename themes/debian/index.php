<div id="chiclet_left">
    <p><?= _("##WELCOMETO##") . $app_name ?></p>
    <p>
        <?= _("##INDEX:GREETINGS##") ?>
        <?= $app_name . _("##INDEX:GREETINGS2##") ?>
    </p>
    
    <ul>
        <?php
            foreach ( $app_links as $key => $value ) {
                echo '<li><a href="' . $value . '">' . $key . '</a></li>';
            }
        ?>
    </ul>
</div>

<div id="chiclet_right">
    <div class="chiclet_usuario">
        <a href="NewUserForm.php">
            <?= _("##INDEX:NEWUSER##") ?>
        </a>
        <br />
	<a href="ResendMailForm.php" class="chiclet_little">
            <?= _("##INDEX:DIDNTGETTHEEMAIL##") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_password">
        <a href="ChangePasswordForm.php">
            <?= _("##INDEX:CHANGEPASSWORD##") ?>
        </a>
        <br />
        <a href="ResetPasswordForm.php" class="chiclet_little">
            <?= _("##INDEX:RESETPASSWORD##") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_olvidar">
	<a href="ForgotUsernameForm.php">
            <?= _("##INDEX:FORGOTUSERNAME##") ?>
        </a>
    </div>
    <div class="chiclet_eliminar">
        <a href="DeleteUserForm.php">
            <?= _("##DELETEUSER##") ?>
        </a>
    </div>
    <div class="chiclet_editar">
        <a href="EditProfileForm.php">
            <?= _("##INDEX:EDITPROFILE##") ?>
        </a>
    </div>
    <div class="chiclet_listar">
        <a href="Browse.php">
            <?= _("##INDEX:BROWSEUSERS##") ?>
        </a>
    </div>
</div>