<div class="visualClear"></div>
</div>
<div id="portal-footer">
    <div id="footer">
        <a href="NewUserForm.php">
            <?= _("Nuevo usuario") ?>
        </a>
        <a href="ChangePasswordForm.php">
            <?= _("Cambiar contraseña") ?>
        </a>
        <a href="ResetPasswordForm.php">
            <?= _("Generar contraseña") ?>
        </a>
        <a href="ForgotUsernameForm.php">
            <?= _("Recordar usuario") ?>
        </a>
        <a href="DeleteUserForm.php">
            <?= _("Eliminar usuario") ?>
        </a>
        <a href="EditProfileForm.php">
            <?= _("Editar Perfil") ?>
        </a>
        <a href="Browse.php">
            <?= _("Listar usuarios") ?>
        </a>
    </div>
    
    <div>
        <?= $app_name . _(" está potenciado por la aplicación ") ?>
        <strong>AGUILAS</strong>.
    </div>
    
    <div>
        <strong>AGUILAS</strong>
        <?= _(" es una aplicación en PHP desarrollada por ") ?>
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
header("Content-language: $language");

?>