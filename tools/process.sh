#!/bin/bash

DOCDIR="../documentation/"
RESTDIR="${DOCDIR}rest/"
INFILES=$( grep -R "@@.*@@" ${DOCDIR} | awk -F: '{print $1}' | sort -u )
SUBS="AUTHOR DATE VERSION URL DESCRIPTION DESCRIPTIONLINES COMMONINTRO RSTLIST LINKLIST"
AUTHOR="Luis Alejandro Martínez Faneyth"
DATE=$( date +%d-%m-%Y )
VERSION=$( cat ../VERSION | grep "VERSION = " | sed 's/VERSION = //g' )
URL="http://code.google.com/p/aguilas"
DESCRIPTION="A web-based LDAP user management system"
DESCRIPTIONLINES="---------------------------------------"
COMMONINTRO=$( cat ${DOCDIR}common.index.in )
RSTLIST=$( ls ${RESTDIR}*.rest | sed "s|${RESTDIR}||g" )
LINKLIST=$( ls ${RESTDIR}*.rest | sed "s|${RESTDIR}|[[|g;s|.rest|]]|g" )

for INFILE in ${INFILES}; do
	for SUB in ${SUBS}; do
		sed -i "s/@@${SUB}@@/$( eval "echo \"\$${SUB}\"" )/g" ${INFILE}
	done
done


