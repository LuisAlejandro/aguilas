<div id="chiclet_left">
    <p><?= _("Welcome to ") . " " . $app_name ?>.</p>
    <p>
        <?= _("From here you will be able to manage directly your users in ") ?>
        <?= $app_name . _(". Creating an account will allow you to connect to the following services:") ?>
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
            <?= _("Request a new user account") ?>
        </a>
        <br />
	<a href="ResendMailForm.php" class="chiclet_little">
            <?= _("I did not get the confirmation e-mail when i requested an account") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_password">
        <a href="ChangePasswordForm.php">
            <?= _("Change user password") ?>
        </a>
        <br />
        <a href="ResetPasswordForm.php" class="chiclet_little">
            <?= _("Reset user password") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_olvidar">
	<a href="ForgotUsernameForm.php">
            <?= _("I forgot my username") ?>
        </a>
    </div>
    <div class="chiclet_eliminar">
        <a href="DeleteUserForm.php">
            <?= _("Delete User") ?>
        </a>
    </div>
    <div class="chiclet_editar">
        <a href="EditProfileForm.php">
            <?= _("Edit user profile") ?>
        </a>
    </div>
    <div class="chiclet_listar">
        <a href="Browse.php">
            <?= _("Browse users") ?>
        </a>
    </div>
</div>