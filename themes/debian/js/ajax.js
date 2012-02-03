function flashRow(obj){obj.bgColor="#FFFF99";}
		
function unFlashRow(obj){obj.bgColor="#F6F6F6";}

function createRequestObject(){
var ro;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
ro = new ActiveXObject("Microsoft.XMLHTTP");
}else{ro = new XMLHttpRequest();}
return ro;
}

var http = createRequestObject();

function sndReq(action,object,place) {
http.open('get', action, false);
http.send(null);
http.onreadystatechange = handleResponse(object,place);
}

function handleResponse(transport,lugar){
transport_rep=transport.replace("+"," ");
if(http.readyState == 4){
if(http.responseText == transport_rep) {
var replaceText = document.getElementById(lugar).value;
document.getElementById(lugar+'_rg_display_section').innerHTML = replaceText;
document.getElementById(lugar+'_rg').style.display = '';
document.getElementById(lugar+'_hv').style.display = 'none';
}else{
alert(http.responseText);
document.getElementById(lugar+'_hv_editing_section').style.display='';
document.getElementById(lugar+'_hv_saving_section').style.display='none';
}
}
}

function sendAjax(obj,quien){
document.getElementById(obj+'_hv_editing_section').style.display='none';
document.getElementById(obj+'_hv_saving_section').style.display='';
var dame_valor = document.getElementById(obj).value;
var cn_html = document.getElementById("cn_rg_display_section").innerHTML;
valor=dame_valor.replace(" ","+");
cn_listo=cn_html.replace(" ","+");
var req = 'Ajax.php?obj_a='+obj+'&val_a='+valor+'&who_a='+quien+"&cn_a="+cn_listo;
sndReq(req,valor,obj);
}

function changeAjax(obj){
document.getElementById(obj+'_hv_editing_section').style.display = '';
document.getElementById(obj+'_hv_saving_section').style.display = 'none';
document.getElementById(obj+'_rg').style.display = 'none';
document.getElementById(obj+'_hv').style.display = '';
}

function cancelAjax(obj){
document.getElementById(obj+'_rg').style.display = '';
document.getElementById(obj+'_hv').style.display = 'none';
}

function update_cn(){
var givenname_ajax = document.getElementById("givenName").value;
var sn_ajax = document.getElementById("sn").value;
document.getElementById("cn_rg_display_section").innerHTML = givenname_ajax+" "+sn_ajax;
}
