#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/maint/ldap-debugging.sh
# DESCRIPTION:  Prints debugging information for an LDAP server
# USAGE: ./ldap-debugging.sh [ADMINDN] [PASS] [SERVER] [BASE]
# COPYRIGHT:
# (C) 2012 Luis Alejandro Martínez Faneyth <luis@huntingbears.com.ve>
# LICENCE: GPL3
# ====================================================================
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# COPYING file for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.
#
# CODE IS POETRY

TMP="$( tempfile )"
LDAPWRITER="${1}"
LDAPPASS="${2}"
LDAPSERVER="${3}"
LDAPBASE="${4}"

echo "\nGetting all entries ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(uid=*)" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without sn ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(!(sn=*))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without cn ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(!(cn=*))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without uid ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(!(uid=*))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without uidNumber ..." | tee ${TMP}
SIN_UIDNUMBER=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(uidNumber=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without loginShell ..." | tee ${TMP}
SIN_LOGINSHELL=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(loginShell=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without mail ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(mail=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without posix objectClass ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(objectClass=top)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without inetOrgPerson objectClass ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(objectClass=inetOrgPerson)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without top objectClass ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(objectClass=top)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without gidNumber ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(gidNumber=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "\nGetting entries without description ..." | tee ${TMP}
ENTRIES=$( ldapsearch -x -w ${LDAPPASS} -D "${LDAPWRITER}" -h ${LDAPSERVER} -b ${LDAPBASE} -LLL "(&(uid=*)(!(description=*)))" | perl -p00e 's/\r?\n //g' | grep "dn: "| sed 's/dn: //g' | sed 's/ /_@_/g' )

for ENTRY in ${ENTRIES}; do
	echo ${ENTRY} | tee ${TMP}
done

echo "Log saved at ${TMP}"
