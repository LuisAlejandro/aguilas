#!/bin/bash -e
#
# ==============================================================================
# PAQUETE: canaima-desarrollador
# ARCHIVO: funciones-desarrollador.sh
# DESCRIPCIÓN: Funciones utilizadas por canaima-desarrollador.sh
# COPYRIGHT:
#  (C) 2010 Luis Alejandro Martínez Faneyth <martinez.faneyth@gmail.com>
# LICENCIA: GPL3
# ==============================================================================
#
# Este programa es software libre. Puede redistribuirlo y/o modificarlo bajo los
# términos de la Licencia Pública General de GNU (versión 3).

function CREAR-FUENTE() {
	#-------------------------------------------------------------#
	# Nombre de la Función: CREAR-FUENTE
	# Propósito: Crear un paquete fuente a partir de un proyecto
	#	    de empaquetamiento debian
	# Dependencias:
	#       - Requiere la carga del archivo ${CONF}
	#-------------------------------------------------------------#

	NO_MOVER=${1}

	# Garanticemos que el directorio siempre tiene escrita la ruta completa
	directorio=${DEV_DIR}${directorio#${DEV_DIR}}
	directorio_nombre=$( basename "${directorio}" )

	# Comprobaciones varias
	# El directorio está vacío
	if [ -z "${directorio#${DEV_DIR}}" ]; then
		ERROR "¡No me dijiste el nombre del directorio a partir del cual deseas generar el paquete fuente!"
		exit 1
	fi

	# El directorio no existe
	if [ ! -d "${directorio}" ]; then
		ERROR "El directorio no existe o no es un directorio."
		exit 1
	fi

	# Determinemos algunos datos de proyecto
	DATOS-PROYECTO

	if [ "${directorio#${directorio%?}}" == "/" ]; then
		directorio=${directorio%?}
	fi

	# Ingresamos a la carpeta del desarrollador
	cd ${DEV_DIR}

	# Corrigiendo nombre del directorio en caso de ser incorrecto
	if [ "${DEV_DIR}${NOMBRE_PROYECTO}-${VERSION_PROYECTO}" != ${directorio} ]; then
		mv ${directorio} ${DEV_DIR}${NOMBRE_PROYECTO}-${VERSION_PROYECTO}
		directorio="${DEV_DIR}${NOMBRE_PROYECTO}-${VERSION_PROYECTO}"
	fi

	directorio=${DEV_DIR}${directorio#${DEV_DIR}}
	directorio_nombre=$( basename "${directorio}" )
	UPVERSION=$( echo ${VERSION_PROYECTO} | sed 's/-.*//g' )

	# Creamos un nuevo directorio .orig
	ADVERTENCIA "Creando paquete fuente ${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz"
		
	# Removemos cualquier carpeta .orig previamente creada
	rm -rf ${DEV_DIR}${NOMBRE_PROYECTO}-${UPVERSION}.orig

	cp -r ${directorio} ${DEV_DIR}${NOMBRE_PROYECTO}-${UPVERSION}.orig

	# Creamos el paquete fuente
	rm -rf ${directorio}.orig/debian/
	rm -rf ${directorio}.orig/.git/
	rm -rf ${directorio}.orig/.pc/

	tar -cvzf ${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz ${NOMBRE_PROYECTO}-${UPVERSION}.orig

	# Movamos las fuentes que estén en la carpeta del desarrollador a su
	# lugar en el depósito
	if [ "${NO_MOVER}" != "no-mover" ]; then
		MOVER fuentes ${NOMBRE_PROYECTO}
	fi

	# Emitimos la notificación
	if [ -e "${DEPOSITO_SOURCES}${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz" ] && [ "${NO_MOVER}" != "no-mover" ]; then
		EXITO "¡Fuente ${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz creada y movida a ${DEPOSITO_SOURCES}!"
	elif [ -e "${DEV_DIR}${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz" ] && [ "${NO_MOVER}" == "no-mover" ]; then
		EXITO "¡Fuente ${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz creada!"
	else
		ERROR "¡Epa! algo pasó durante la creación de ${NOMBRE_PROYECTO}_${UPVERSION}.orig.tar.gz"
	fi
}

function CREAR-JAULA() {

	JAULA_DIST=${1}
	JAULA_ARCH=${2}
	JAULA_MIRROR=${3}

	case ${JAULA_DIST} in

	lenny)
		JAULA_DIST="lenny"
	;;
	
	aponwao)
		JAULA_DIST="lenny"
		OTHERMIRROR="--other=\"deb http://repositorio.canaima.softwarelibre.gob.ve aponwao usuarios\""
	;;
	squeeze)
		JAULA_DIST="squeeze"
	;;
	roraima)
		JAULA_DIST="squeeze"
		OTHERMIRROR="--other=\"deb http://repositorio.canaima.softwarelibre.gob.ve roraima usuarios\""
		EXTRAPACKAGES="canaima-desarrollador"
	;;
	auyantepui)
		JAULA_DIST="squeeze"
		OTHERMIRROR="--other=\"deb http://repositorio.canaima.softwarelibre.gob.ve auyantepui usuarios\""
		EXTRAPACKAGES="canaima-desarrollador"
	;;
	kukenan)
		JAULA_DIST="squeeze"
		OTHERMIRROR="--other=\"deb http://repositorio.canaima.softwarelibre.gob.ve kukenan usuarios\""
		EXTRAPACKAGES="canaima-desarrollador"
	;;
	wheezy|sid|experimental)
		JAULA_DIST="${JAULA_DIST}"
	;;
	''|*)
		ADVERTENCIA "No especificaste una distribución válida para generar la jaula."
		ADVERTENCIA "Escogiendo \"squeeze\" por defecto."
		JAULA_DIST="squeeze"
		OTHERMIRROR="--other=\"deb http://repositorio.canaima.softwarelibre.gob.ve auyantepui usuarios\""
		EXTRAPACKAGES="canaima-desarrollador"
	;;
	esac

	case ${JAULA_ARCH} in

	i386|i686|386|686|486|x86)
		JAULA_ARCH="i386"
	;;
	ia64|x86_64|64bits|64|amd64)
		JAULA_ARCH="amd64"
	;;
	''|*)
		JAULA_ARCH=$( uname -m )
		ADVERTENCIA "No especificaste una arquitectura válida para generar la jaula."
		ADVERTENCIA "Escogiendo \"${JAULA_ARCH}\" por defecto."
	;;
	esac

	if [ -z ${JAULA_MIRROR} ]; then
		JAULA_MIRROR="http://universo.canaima.softwarelibre.gob.ve"
                ADVERTENCIA "No especificaste un mirror debian para generar la jaula."
                ADVERTENCIA "Escogiendo \"${JAULA_MIRROR}\" por defecto."
	fi

	ARCH=${JAULA_ARCH}
	DIST=${JAULA_DIST}
	DISTRIBUTION=${JAULA_DIST}
	GIT_PBUILDER_OPTIONS="--buildplace=\"${DEVDIR}JAULAS/BUILD/\""
	COWBUILDER_BASE="${DEVDIR}JAULAS/base-${JAULA_DIST}-${JAULA_ARCH}.cow"
	export ARCH=${JAULA_ARCH}
	export DIST=${JAULA_DIST}
	export DISTRIBUTION=${JAULA_DIST}
	export GIT_PBUILDER_OPTIONS="--buildplace=\"${DEVDIR}JAULAS/BUILD/\""
	export COWBUILDER_BASE="${DEVDIR}JAULAS/base-${JAULA_DIST}-${JAULA_ARCH}.cow"

	if [ ! -e ${COWBUILDER_BASE} ]; then
		ADVERTENCIA "Creando jaula en \"${COWBUILDER_BASE}\""
		git-pbuilder create --distribution="${JAULA_DIST}" --architecture="${JAULA_ARCH}" \
		--mirror="${JAULA_MIRROR}" ${OTHERMIRROR} --components="main contrib non-free" \
		--debootstrap="debootstrap" --basepath="${COWBUILDER_BASE}" \ 
		--extrapackages="canaima-base ${EXTRAPACKAGES}"
	else
		ADVERTENCIA "La jaula \"${COWBUILDER_BASE}\" ya existe, continuando."
	fi

}

function EMPAQUETAR() {
	#-------------------------------------------------------------#
	# Nombre de la Función: EMPAQUETAR
	# Propósito: Crear un paquete binario a partir de un proyecto
	#	    de empaquetamiento debian
	# Dependencias:
	# 	- Requiere la carga del archivo ${CONF}
	#	- git-buildpackage
	#-------------------------------------------------------------#

	if [ -z ${DEV_GPG} ]; then
		CDSIGN="-us -uc"
	else
		CDSIGN="-k${DEV_GPG}"
	fi

	if [ "${prueba}" == "--prueba" ]; then
		TYPEREL="--snapshot"
	else
		TYPEREL="--release"
		CDTAGGING="--git-tag --git-retag"
	fi

	if [ -z "${directorio}" ]; then
		directorio="$( pwd )"
		ADVERTENCIA "No especificaste un directorio a empaquetar, asumiendo que es $( pwd )."
	fi

	if [ "${no_devdir}" == "--no-devdir" ]; then
		DEV_DIR="$( dirname ${directorio} )"
		ADVERTENCIA "Temporalmente usando \"${DEV_DIR}\" como carpeta del desarrollador."
		CHECK
	fi

	# Garanticemos que el directorio siempre tiene escrita la ruta completa
	directorio=${DEV_DIR}${directorio#${DEV_DIR}}
	directorio_nombre=$( basename "${directorio}" )

	# El directorio no existe
	if [ ! -e "${directorio}" ]; then
		ERROR "¡EPA! La carpeta \"${directorio}\" no existe."
		exit 1
	fi

	# El directorio no es un directorio
	if [ ! -d "${directorio}" ]; then
		ERROR "¡\"${directorio}\" no es un directorio!"
		exit 1
	fi

	# Tal vez te comiste el parámetro directorio o especificaste un directorio con espacios
	if [ $( echo "${directorio#${DEV_DIR}}" | grep -c " " ) != 0 ]; then
		ERROR "Sospecho dos cosas: o te saltaste el nombre del directorio, o especificaste un directorio con espacios."
		exit 1
	fi

	# No especificaste un mensaje para el commit
	if [ -z "${mensaje}" ]; then
		mensaje="auto"
		ADVERTENCIA "Mensaje de commit vacío. Autogenerando."
	fi

	case ${procesadores} in
	auto)
		procesadores=$( grep -cE "^processor" /proc/cpuinfo )
		ADVERTENCIA "Utilizando máxima capacidad de procesamiento para construir el proyecto (${procesadores})."
	;;
	*)
		ADVERTENCIA "Utilizando ${procesadores} para construir el proyecto"
	;;
	'')
		ADVERTENCIA "No especificaste número de procesadores a utilizar, asumiendo 1 solo procesador."
		procesadores="0"
	;;
	esac
	
	# Obtengamos datos básicos del proyecto
	DATOS-PROYECTO

	# Movemos todo a sus depósitos
	MOVER debs ${NOMBRE_PROYECTO}
	MOVER logs ${NOMBRE_PROYECTO}
	MOVER fuentes ${NOMBRE_PROYECTO}

	# Accedemos al directorio
	cd ${directorio}

	# Hacemos commit de los (posibles) cambios hechos
	REGISTRAR

	# Lo reflejamos en debian/changelog
	if [ "${NO_COMMIT}" == "0" ]; then
		GIT-DCH ${TYPEREL}
		REGISTRAR
	fi

	# Creamos el paquete fuente
	CREAR-FUENTE no-mover

	cd ${directorio}

	# Cálculo de los threads (n+1)
	threads=$[ ${procesadores}+1 ]
	CDJOBS="-j${threads}"
	CDCONCURRENCY="CONCURRENCY_LEVEL=${threads}"
	export ${CDCONCURRENCY}

	if [ "${jaula}" == "--jaula" ]; then
		CREAR-JAULA "${jaula_dist}" "${jaula_arch}" "${jaula_mirror}"
		CDBUILDER="--git-pbuilder --git-dist=\"${JAULA_DIST}\" --git-arch=\"${JAULA_ARCH}\""
	fi

	cd ${directorio}

	# Empaquetamos
	${CDCONCURRENCY} git-buildpackage ${CDBUILDER} ${CDSIGN} -tc ${CDTAGGING} ${CDJOBS} || E_EMPAQUETAR=1

	git clean -fd
	git reset --hard

	if [ -z ${E_EMPAQUETAR} ]; then
		# Movemos todo a sus depósitos
		MOVER debs ${NOMBRE_PROYECTO}
		MOVER logs ${NOMBRE_PROYECTO}
		MOVER fuentes ${NOMBRE_PROYECTO}

		# Hacemos push
		if [ "${no_enviar}" != "--no-enviar" ] && [ "${prueba}" != "--prueba" ]; then
			ENVIAR
		fi

		EXITO "¡ÉXITO! \"${NOMBRE_PROYECTO}\" empaquetado correctamente."

	elif [ "${E_EMPAQUETAR}" == "1" ]; then
		ERROR "Ocurrió un error empaquetando \"${NOMBRE_PROYECTO}\" revisa el log para mayor información."
	fi

	# Nos devolvemos a la carpeta del desarrollador
	cd ${DEV_DIR}
}
