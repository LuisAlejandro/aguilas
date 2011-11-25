<?php include("header.php"); ?>


<h2>Recordar Nombre de Usuario</h2>

<p>
  Por favor introduce a continuación la dirección de correo electrónico que utilizaste para registrar tu usuario.
</p>

<form method='post' action='olvido_usuario2.php'>
  <table>
  <tr>
    <td class="px160">Correo electrónico</td>
    <td class="px120">p. ej., eme@terio.org.ve</td>
    <td class="px640">
    <span id="mail_js">
		<input type='text' name='mail' id='mail' class="input5" />
    	<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
		<span class="textfieldMaxCharsMsg">Excediste el número máximo de 60 Caracteres.</span>
		<span class="textfieldInvalidFormatMsg">Formato Inválido.</span>
    </span>
    </td>
  </tr>
  <tr>
    <td class="px160">Verificación</td>
    <td class="px120"><img src="captcha.php" border="0"></td>
    <td class="px640">
    <span id="image_captcha_js">
		<input name='image_captcha' id='image_captcha' class="input5" />
    	<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
    	<span class="textfieldMaxCharsMsg">Excediste el número máximo de 8 Caracteres.</span>
    </span>
    </td>
  </tr>
  </table>
    <input type='submit' value='ENVIAR' class="boton" />
</form>

<script type="text/javascript">
var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});
</script>

<?php include("footer.php"); ?>
