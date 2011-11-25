<?php

foreach($_POST as $nombre_campo => $valor){
$asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
if(in_array($nombre_campo,$op_permitidas)){
eval($asignacion);
}else{
?>
<div class="error">
Opción "<?php echo $nombre_campo;?>" desconocida.
</div>
<?php
}
}

foreach($_GET as $nombre_campo => $valor){
$asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
if(in_array($nombre_campo,$op_permitidas)){
eval($asignacion);
}else{
?>
<div class="error">
Opción "<?php echo $nombre_campo;?>" desconocida.
</div>
<?php
}
}

?>
