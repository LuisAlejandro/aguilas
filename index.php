<?php include("header.php"); ?>

<div id="chiclet_left">
<p>Bienvenidos al Sistema de Registro Único de la Plataforma Colaborativa Canaima.</p>
<p>Desde acá podrás gestionar directamente tus datos de usuario en la Plataforma Colaborativa Canaima. Con una cuenta canaima estarás conectado a los siguientes servicios:</p>
<ul>
<li><a href="http://canaima.softwarelibre.gob.ve/">Portal Principal</a></li>
<li><a href="http://wiki.canaima.softwarelibre.gob.ve/">Enciclopedia Colaborativa</a></li>
<li><a href="http://proyectos.canaima.softwarelibre.gob.ve/canaima">Reporte de Bugs</a></li>
<li><a href="http://ideas.canaima.softwarelibre.gob.ve/">Ideas</a></li>
</ul>
</div>

<div id="chiclet_right">
  <div class="chiclet_usuario">
	<a href="nuevo_usuario.php">Solicitar una nueva cuenta de usuario</a><br />
	<a href="resend_nuevo_usuario.php" class="chiclet_little">No me llegó el correo de confirmación cuando solicité una nueva cuenta</a>
  </div>
  <div class="chiclet_password">
  	<a href="cambiar_password.php">Cambiar contraseña de usuario</a><br />
	<a href="generar_password.php" class="chiclet_little">Generar una nueva contraseña</a><br />
  </div>
  <div class="chiclet_olvidar">
	<a href="olvido_usuario.php">Olvidé mi nombre de usuario</a>
  </div>
  <div class="chiclet_eliminar">
	<a href="eliminar_usuario.php">Eliminar usuario</a>
  </div>
  <div class="chiclet_editar">
	<a href="editar_perfil.php">Ver/Editar perfil de usuario</a>
  </div>
  <div class="chiclet_listar">
	<a href="listar_usuarios.php">Listar todos los usuarios de la plataforma</a>
  </div>
</div>


<?php include("footer.php"); ?>
