<div id="chiclet_left">
    <p><?= _("Bienvenidos a") . $app_name ?></p>
    <p>
        <?= _("Desde acá podrás gestionar directamente tus datos de usuario en ") ?>
        <?= $app_name . _(". Creando una cuenta estarás conectado a los siguientes servicios:") ?>
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
            <?= _("Solicitar una nueva cuenta de usuario") ?>
        </a>
        <br />
	<a href="ResendMailForm.php" class="chiclet_little">
            <?= _("No me llegó el correo de confirmación cuando solicité una nueva cuenta") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_password">
        <a href="ChangePasswordForm.php">
            <?= _("Cambiar contraseña de usuario") ?>
        </a>
        <br />
        <a href="ResetPasswordForm.php" class="chiclet_little">
            <?= _("Generar una nueva contraseña") ?>
        </a>
        <br />
    </div>
    <div class="chiclet_olvidar">
	<a href="ForgotUsernameForm.php">
            <?= _("Olvidé mi nombre de usuario") ?>
        </a>
    </div>
    <div class="chiclet_eliminar">
        <a href="DeleteUserForm.php">
            <?= _("Eliminar usuario") ?>
        </a>
    </div>
    <div class="chiclet_editar">
        <a href="EditProfileForm.php">
            <?= _("Ver/Editar perfil de usuario") ?>
        </a>
    </div>
    <div class="chiclet_listar">
        <a href="Browse.php">
            <?= _("Listar todos los usuarios") ?>
        </a>
    </div>
</div>