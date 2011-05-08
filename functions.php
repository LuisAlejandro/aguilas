<?php

class logfile{
function write($the_string,$the_file){
if($fh=fopen($the_file.".log","a+")){
fwrite($fh,$the_string,strlen($the_string));
fclose($fh);
return(true);}
else{return(false);}
}
}

function asistente_ajax($objeto_asist,$etiqueta_input,$contenido_input,$show_edit,$quien_asist){

?>
<tr>
	<td class="px160"><?php echo $etiqueta_input; ?></td>
	<td class="px120"><?php if($show_edit){ ?>pulse sobre el campo para editarlo<?php }else{ ?>este campo no puede editarse<?php } ?></td>
	<td class="px640">
	<div>
		<table <?php if($show_edit){ ?>class="infoBox" cellSpacing="2" cellPadding="3"<?php }else{ ?>class="infoBox_null"<?php } ?>>
		<tr valign="middle">
			<td id="<? echo $objeto_asist; ?>_rg" <?php if($show_edit){ ?>onmouseover="flashRow(this);" onclick="changeAjax('<? echo $objeto_asist; ?>');" onmouseout="unFlashRow(this);"<?php } ?>>
				<div class="superBigSize" id="<? echo $objeto_asist; ?>_rg_display_section"><? echo $contenido_input; ?></div>
			</td>
			<?php if($show_edit){ ?>
			<td id="<? echo $objeto_asist; ?>_hv">
				<div id="<? echo $objeto_asist; ?>_hv_editing_section">
					<input class="superBigSize editMode" id="<? echo $objeto_asist; ?>" name="<? echo $objeto_asist; ?>" value="<? echo $contenido_input; ?>" <?php if($objeto_asist=="givenName"||$objeto_asist=="sn"){ ?>onkeyup="actualiza_cn();"<?php } ?> />&nbsp;
					<input class="AjaxButton" onclick="sendAjax('<? echo $objeto_asist; ?>','<? echo $quien_asist; ?>');" type="button" value="Guardar" />&nbsp;
					<input class="AjaxButton" onclick="cancelAjax('<? echo $objeto_asist; ?>');" type="button" value="Cancelar" />
				</div>
				<span class="savingAjaxWithBackground" id="<? echo $objeto_asist; ?>_hv_saving_section">&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;</span>
				<script type="text/javascript">
					document.getElementById('<? echo $objeto_asist; ?>_hv').style.display = 'none';
					document.getElementById('<? echo $objeto_asist; ?>_hv_saving_section').style.display = 'none';
				</script>
			</td>
			<?php } ?>
		</tr>
		</table>
	</div>
	</td>
</tr>

<?php

}

?>
