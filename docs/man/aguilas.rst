===========
**AGUILAS**
===========
------------------------------------------------------------------------------
Asistente para la Gestión de Usuarios con Interfaz para LDAP Amigable y Segura
------------------------------------------------------------------------------

:Author: Luis Alejandro Martínez Faneyth <martinez.faneyth@gmail.com>
:Date:   2011-04-24
:Copyright: Libre uso, modificación y distribución (GPL3)
:Version: 1.1
:Manual section: 1
:Manual group: Web
:Tags: ldap, aguilas, gestión de usuarios, ajax, php, mysql
:Support: http://www.huntingbears.com.ve/aguila-asistente-para-la-gestion-de-usuarios-con-interfaz-para-ldap-amigable-y-segura.html
:Summary: AGUILAS es un sistema de registro web basado en tecnologías libres (OpenLDAP, MYSQL, PHP).

**DESCRIPCIÓN**
===============

AGUILAS es una aplicación escrita principalmente en PHP, aunque tiene fragmentos de javascript, SQL, hojas de estilo y por supuesto, HTML. Es un sistema de registro centralizado que permite gestionar usuarios en una plataforma de autenticación LDAP, utilizando como apoyo bases de datos MYSQL en los casos donde sea necesario (registros temporales).

AGUILAS tiene las siguientes funcionalidades:

* Creación de usuarios. Permite crear usuarios en el LDAP, para lo cual se siguen los siguientes pasos:
		1.- Se solicita al usuario los siguientes datos:
				- Nombres
				- Apellidos
				- Nombre de Usuario
				- Correo Electrónico
				- Contraseña
				- Contraseña (repetir)
				- Imagen de Verificación (CAPTCHA)
		2.- Se validan los datos (caracteres inválidos, existencia del usuario, entre otras).
		3.- Se introducen los datos temporalmente en una base de datos MYSQL.
		4.- Se envía un correo al usuario para que confirme la creación del usuario mediante un enlace.
		5.- Si la confirmación es exitosa (también se validan los datos provenientes del enlace), los datos se extraen de la base de datos MYSQL para introducirlos al LDAP.
		6.- Se envía un correo de éxito al usuario.
		7.- Se escribe un resumen de lo ocurrido en el archivo de registros (log) correspondiente.
		8.- Se borra la entrada temporal en la base de datos MYSQL.
		9.- Se informa al usuario en pantalla de que el usuario ha sido creado.

* Reenvío del correo de confirmación de creación de nuevo usuario.
		1.- Se solicita al usuario los siguientes datos:
				- Correo Electrónico con el que realizó la petición de nuevo usuario.
				- Imagen de Verificación (CAPTCHA).
		2.- Se validan los datos (caracteres inválidos, existencia del usuario, entre otras).
		3.- Se busca en la base de datos MYSQL la última petición realizada con el correo provisto por el usuario.
		4.- Si existen resultados, se reenvía el correo de confirmación, de lo contrario se sugiere al usuario volver a hacer la petición.

* Generación de Contraseña.
		1.- Se solicita al usuario los siguientes datos:
				- Nombre de Usuario
				- Correo Electrónico
				- Imagen de Verificación (CAPTCHA)
		2.- Se validan los datos (caracteres inválidos, existencia del usuario, coincidencia de datos, entre otras).
		3.- Se introducen los datos temporalmente en una base de datos MYSQL.
		4.- Se envía un correo al usuario para que confirme la generación de la contraseña mediante un enlace.
		5.- Si la confirmación es exitosa (también se validan los datos provenientes del enlace), se genera una contraseña de alta seguridad y se envía un correo al usuario informándole de su nueva contraseña.
		7.- Se escribe un resumen de lo ocurrido en el archivo de registros (log) correspondiente.
		8.- Se borra la entrada temporal en la base de datos MYSQL.
		9.- Se informa al usuario en pantalla de su nueva contraseña.
		
* Cambio de Contraseña.
		1.- Se solicita al usuario los siguientes datos:
				- Nombre de Usuario
				- Correo Electrónico
				- Contraseña Actual
				- Contraseña Nueva
				- Contraseña Nueva (repetir)
				- Imagen de Verificación (CAPTCHA)
		2.- Se validan los datos (caracteres inválidos, existencia del usuario, coincidencia de los datos, contraseñas iguales, entre otras).
		3.- Se hace el cambio de contraseña en el LDAP.
		6.- Se envía un correo de éxito al usuario.
		7.- Se escribe un resumen de lo ocurrido en el archivo de registros (log) correspondiente.
		9.- Se informa al usuario en pantalla que su contraseña ha sido cambiada.
		
* Recordatorio del usuario.
		1.- Se solicita al usuario los siguientes datos:
				- Correo Electrónico
				- Imagen de Verificación (CAPTCHA)
		2.- Se validan los datos (caracteres inválidos, existencia del correo, entre otras).
		3.- Se realiza la búsqueda en el LDAP de las cuentas asociadas al correo provisto por el usuario.
		6.- Se muestran los resultados.
		
* Eliminación del usuario.
		1.- Se solicita al usuario los siguientes datos:
				- Nombre de Usuario
				- Correo Electrónico
				- Contraseña Actual
				- Imagen de Verificación (CAPTCHA)
		2.- Se validan los datos (caracteres inválidos, existencia del usuario, coincidencia de los datos, entre otras).
		3.- Se elimina el usuario en el LDAP.
		6.- Se envía un correo de éxito al usuario.
		7.- Se escribe un resumen de lo ocurrido en el archivo de registros (log) correspondiente.
		9.- Se informa al usuario en pantalla que su usuario ha sido eliminado.
		
* Editar perfil de usuario.
* Listar todos los usuario registrados.
* Buscar usuarios.


**REQUISITOS DEL SISTEMA**
==========================

* Base de datos MYSQL.
* Implementación de LDAP (OpenLDAP preferiblemente).
* PHP5
* Apache2
* imagemagick
* libmagickcore3-extra
* icoutils
* xcftools

**INSTALACIÓN**
===============

1.- Descomprime el archivo aguilas-1.1.tar.gz en la carpeta de tu preferencia.

2.- Modifica el archivo config.php con los datos de tu configuración.

3.- En una consola de root, dentro de la carpeta recien descomprimida, ejecutar el comando "make install".

4.- Listo, tu sistema se encuentra instalado. Si estás en un servidor, puedes acceder a el desde el navegador a través de http://<tudominio.com>/aguilas/ o, si estás en tu pc personal, puedes acceder a través de http://localhost/aguilas/.

**CHANGELOG**
=========

* 1.0 (24 de Abril de 2011):
	- Primera versión Pública.
* 1.1 (25 de Noviembre de 2011):
	- Mejoras varias:
    
        * Cambio de permisos a *.php
        * Mejora de la lectura de la configuración
        * Auto-incremento del uidNumber
        * Perfeccionamiento del código


**SOPORTE**
===========

Para obtener soporte respecto a la aplicación, puedes visitar la dirección de la aplicación (ver encabezado) y realizar tu pregunta en sección de comentarios.
