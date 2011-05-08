<?php include("header.php"); ?>


<h2>Solicitar una Cuenta de Usuario</h2>

<p>
  Por favor complete el siguiente formulario para solicitar una cuenta de usuario la Plataforma Colaborativa del Proyecto Canaima. Con esta cuenta podrá autenticarse exitosamente en todos los servicios de la Plataforma. En la segunda columna del formulario hemos colocado algunos ejemplos para ayudarle en el llenado del formulario. Por favor, siga las indicaciones del sistema para culminar con éxito el proceso.
</p>

<form method='post' action='nuevo_usuario_confirmar.php'>
  <table>
  <tr>
    <td class="px160">Nombres</td>
    <td class="px120">p. ej., Emeterio Ildefonso</td>
    <td class="px640">
		<span id="givenName_js">
			<input type='text' name='givenName' id="givenName" class="input5" />
			<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
			<span class="textfieldMaxCharsMsg">Excediste el número máximo de 60 Caracteres.</span>
		</span>
	</td>
  </tr>
  <tr>
    <td class="px160">Apellidos</td>
    <td class="px120">p. ej., Alcántara Carrasco</td>
    <td class="px640">
    <span id="sn_js">
			<input type='text' name='sn' id='sn' class="input5" />
			<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
			<span class="textfieldMaxCharsMsg">Excediste el número máximo de 60 Caracteres.</span>
    </span>
    </td>
  </tr>
  <tr>
    <td class="px160">Nombre de usuario</td>
    <td class="px120">p. ej., emeteri0</td>
    <td class="px640">
	<span id="uid_js">
		<input type='text' name='uid' id='uid' class="input5" />
		<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
		<span class="textfieldMaxCharsMsg">Excediste el número máximo de 60 Caracteres.</span>
    </span>
	</td>
  </tr>
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
    <td class="px160">Contraseña</td>
    <td class="px120">al menos ocho caracteres</td>
    <td class="px640">
    <span id="userPassword_js">
		<input type='password' name='userPassword' id='userPassword' class="input5" />
    	<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
		<span class="textfieldMaxCharsMsg">Excediste el número máximo de 20 Caracteres.</span>
		<span class="textfieldMinCharsMsg">La contraseña debe tener al menos 8 Caracteres.</span>
    </span>
    </td>
  </tr>
  <tr>
    <td class="px160">Contraseña (repítala)</td>
    <td class="px120">al menos ocho caracteres</td>
    <td class="px640">
    <span id="userPasswordBis_js">
		<input type='password' name='userPasswordBis' id='userPasswordBis' class="input5" />
    	<span class="textfieldRequiredMsg">No puedes dejar éste campo en blanco.</span>
		<span class="textfieldInvalidFormatMsg">Las contraseñas no coinciden.</span>
		<span class="textfieldMaxCharsMsg">Excediste el número máximo de 20 Caracteres.</span>
		<span class="textfieldMinCharsMsg">La contraseña debe tener al menos 8 Caracteres.</span>
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

var passwordIgual = function(value, options){
	var other_value = document.getElementById('userPassword').value;
	if (value != other_value){
		return false;
	}
	return true;
}

var givenName_var = new Spry.Widget.ValidationTextField("givenName_js", "none", {validateOn:["blur"], maxChars:60});
var sn_var = new Spry.Widget.ValidationTextField("sn_js", "none", {validateOn:["blur"], maxChars:60});
var uid_var = new Spry.Widget.ValidationTextField("uid_js", "none", {validateOn:["blur"], maxChars:60});
var mail_var = new Spry.Widget.ValidationTextField("mail_js", "email", {validateOn:["blur"], maxChars:60});
var userPassword_var = new Spry.Widget.ValidationTextField("userPassword_js", "none", {validateOn:["blur"], maxChars:20, minChars:8});
var userPasswordBis_var = new Spry.Widget.ValidationTextField("userPasswordBis_js", "custom", {validation: passwordIgual, validateOn:["blur"], maxChars:20, minChars:8});
var image_captcha_var = new Spry.Widget.ValidationTextField("image_captcha_js", "none", {validateOn:["blur"], maxChars:8});

</script>

<?php include("footer.php"); ?>
