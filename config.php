<?php

/*******************************************
 ** ARCHIVO DE CONFIGURACIÓN PARA AGUILAS **
 *******************************************/

//MYSQL-------------------------------------------------
//Servidor donde se encuentra la base de datos MYSQL
$mysql_host = 'localhost';
//Usuario con permisos para crear tablas
$mysql_user = 'root';
//Contraseña del usuario $mysql_user
$mysql_pass = '123456';
//Nombre de una base de datos existente y vacía
$mysql_dbname = 'canaima';

//LDAP-------------------------------------------------
//DN de un usuario admin de LDAP
$ldap_dn = "cn=admin,dc=nodomain";
//Contraseña del usuario
$ldap_pass = "123456";
//Dominio Base
$ldap_base = "dc=nodomain";
//Servidor donde se encuentra el Directorio LDAP
$ldap_server = "localhost";
//Array asociativo de Nombre Grupo => Código Grupo (gidNumber)
$ldap_gid=array("Gente"=>"100","Administradores"=>"200");
$ldap_gid_flip=array_flip($ldap_gid); // No se toca

//Dominio web
$URL_base="localhost";

?>
