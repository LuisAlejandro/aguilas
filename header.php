<?php 

if(extension_loaded('zlib') && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){ob_start('ob_gzhandler');}else{ob_start();}

function compress_html($html){
preg_match_all('!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!',$html,$pre);
$html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);
$html = preg_replace('#<!--[^\[].+-->#', ' ', $html);
$html = preg_replace('/[\r\n\t]+/', ' ', $html);
$html = preg_replace('/>[\s]+</', '><', $html);
$html = preg_replace('/[\s]+/', ' ', $html);
$find = array("/á/","/é/","/í/","/ó/","/ú/","/Á/","/É/","/Í/","/Ó/","/Ú/","/ñ/","/Ñ/","/¡/","/¿/");
$replace = array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&ntilde;","&Ntilde;","&iexcl;","&iquest;");
$html = preg_replace($find,$replace,$html);
if(!empty($pre[0]))
foreach($pre[0] as $tag)
$html = preg_replace('!#pre#!', $tag, $html,1);
return $html;
}

//echo "<!-- Optimizado con el Script HuntingSpeed -->\n\n";

//ob_start("compress_html");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Sistema de Registro Único de la Plataforma Colaborativa</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex,nofollow" />
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel='stylesheet' type='text/css' href='css/estilo.css' media='screen' />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript" src="js/lavalamp.js"></script>
<script type="text/javascript" src="js/validation.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>

</head>

<body>
	<div id="globalWrapper">
		<div id="column-content">
		<div id="portal-top">
			<ul id="p-personal">
				<li><a href="nuevo_usuario.php">Nuevo usuario</a></li>
				<li><a href="cambiar_password.php">Cambiar contraseña</a></li>
				<li><a href="generar_password.php">Generar contraseña</a></li>
				<li><a href="olvido_usuario.php">Recordar usuario</a></li>
				<li><a href="eliminar_usuario.php">Eliminar usuario</a></li>
				<li><a href="editar_perfil.php">Editar Perfil</a></li>
				<li><a href="listar_usuarios.php">Listar usuarios</a></li>
            </ul>
		</div>
		<div id="portal-searchbox">

        <div id="p-logo">
                <a style="background-image: url(images/canaima.png);" href="http://canaima.softwarelibre.gob.ve/" title="Visitar la Portada [z]" accesskey="z"></a>
        </div>
<form action="buscar_usuarios.php" id="searchform" style="white-space: nowrap;">
	<div class="LSBox">
	<div id="clock">Introduce un término de búsqueda y presiona enter.</div>
	<input id="searchInput" value="Buscar Usuario" name="searchInput" type="text" onfocus="this.value=(this.value=='Buscar Usuario') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Buscar Usuario' : this.value;"  />
	</div>
</div>
</form>
</div>
<img src="images/banner.jpg" />

<div id="menu">
<ul id="portal-globalnav">
<li><a href="index.php">Registro</a></li>
<li><a href="http://canaima.softwarelibre.gob.ve/">Portal</a></li>
<li><a href="http://wiki.canaima.softwarelibre.gob.ve/">Wiki</a></li>
<li><a href="http://proyectos.canaima.softwarelibre.gob.ve/canaima">Reporte de Bugs</a></li>
<li><a href="http://cayapa.canaima.softwarelibre.gob.ve/">Cayapa</a></li>
<li><a href="http://ideas.canaima.softwarelibre.gob.ve/">Ideas</a></li>
</ul>
</div>

<div id="content">
<div class="visualClear"></div>
