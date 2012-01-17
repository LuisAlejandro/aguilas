#!/bin/bash

ROOTDIR="$( pwd )"
DOCDIR="${ROOTDIR}/documentation"
RESTDIR="${DOCDIR}/rest"
VERSIONFILE="${ROOTDIR}/VERSION"
INFILES=$( grep -R "@@.*@@" ${DOCDIR} | awk -F: '{print $1}' | sort -u | grep "^.*\.in$" )
SUBS="AUTHOR DATE VERSION URL DESCRIPTION DESCRIPTIONLINES COMMONINTRO RSTLIST LINKLIST"
AUTHOR="Luis Alejandro Martínez Faneyth"
DATE=$( date +%d-%m-%Y )
VERSION=$( cat ${VERSIONFILE} | grep "VERSION = " | sed 's/VERSION = //g;s/+.*//g' )
URL="http://code.google.com/p/aguilas"
DESCRIPTION="A web-based LDAP user management system"
DESCRIPTIONLINES="---------------------------------------"
COMMONINTRO=$( cat ${DOCDIR}/common.index.in | sed ':a;N;$!ba;s/\n/_______/g' )
RSTLIST=$( ls ${RESTDIR}/*.rest | sed "s|${RESTDIR}/||g" )
LINKLIST=$( ls ${RESTDIR}/*.rest | sed "s|${RESTDIR}/|[[|g;s|.rest|]]|g" )

for INFILE in ${INFILES}; do
	for SUB in ${SUBS}; do
		OUTFILE=$( echo ${INFILE} | sed 's|\.in||g')
		cp ${INFILE} ${OUTFILE}
		sed -i "s|@@${SUB}@@|$( eval "echo \"\$${SUB}\"" )|g" ${OUTFILE}
		sed -i "s|_______|\n|g" ${OUTFILE}
	done
done
