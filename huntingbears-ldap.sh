#!/bin/bash

# Color Verde
VERDE="\e[1;32m"
# Color Rojo
ROJO="\e[1;31m"
# Color Amarillo
AMARILLO="\e[1;33m"
# Negrita
BOLD="\e[1m"
# Caracter de fin de línea
FIN="\e[0m"

function ERROR() {
echo -e ${ROJO}${1}${FIN}
}

function ADVERTENCIA() {
echo -e ${AMARILLO}${1}${FIN}
}

function EXITO() {
echo -e ${VERDE}${1}${FIN}
}

WRITER_DN="uid=admin,dc=nodomain"
PASS_DN="123456"
DOMINIO="localhost"
BASE="dc=nodomain"

ADVERTENCIA "Obteniendo entradas especiales, sin User ID..."
CUSTOM_ENTRIES=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(!(uid=*))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

# Obtenemos los DN de las entradas estandar que no tienen uidNumber
SIN_UIDNUMBER=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(uidNumber=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_LOGINSHELL=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(loginShell=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

MAIL_REGISTERED=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(registeredAddress=*)(!(mail=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

CON_REG_ADDR=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(registeredAddress=*))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_MAIL=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(mail=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_POSIX=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(objectClass=top)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_INETORGPERSON=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(objectClass=inetOrgPerson)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_TOP=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(objectClass=top)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_GIDNUMBER=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(gidNumber=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

SIN_DESCRIPTION=$( ldapsearch -x -w ${PASS_DN} -D "${WRITER_DN}" -h ${DOMINIO} -b ${BASE} -LLL "(&(uid=*)(!(description=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )


echo -e ${CUSTOM_ENTRIES}
echo -e ${SIN_UIDNUMBER}

for DEL_CUSTOM in ${CUSTOM_ENTRIES}; do
[ ${DEL_CUSTOM} != ${BASE} ] && SIN_UIDNUMBER=$( echo ${SIN_UIDNUMBER} | sed 's/\b'${DEL_CUSTOM}'\b//g' )

for DEL_UIDN in ${C}; do
[ ${DEL_CUSTOM} != ${BASE} ] && SIN_UIDNUMBER=$( echo ${SIN_UIDNUMBER} | sed 's/\b'${DEL_CUSTOM}'\b//g' )


done


done

#echo -e ${CUSTOM_ENTRIES}
#echo -e ${SIN_UIDNUMBER}

case ${1} in

info)

;;


normalizar)

;;

esac
