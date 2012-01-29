#!/bin/bash -e
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/gen-conf.sh
# DESCRIPTION:	Asks the user to fill in the configuration information
#		to generate the setup/config.php file.
# USAGE: ./tools/gen-conf.sh
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

ROOTFLDR="$( pwd )"
SAMPLE="${ROOTFLDR}/setup/config.php.example"
FILE="${ROOTFLDR}/setup/config.php"
VARTMP="$( tempfile )"
COMTMP="$( tempfile )"

echo
echo "We are going to ask you a couple of questions regarding AGUILAS configuration."
echo "If you have not yet defined your LDAP/MYSQL server, then cancel"
echo "the process and check the INSTALL file for more instructions."

echo
echo "Are you ready to configure AGUILAS? Press Y to continue or N to cancel."

read -p "[Y/N]"
CONTINUE="${REPLY}"

if [ "${CONTINUE}" == "Y" ]; then
	
	cp ${SAMPLE} ${FILE}
	cat ${SAMPLE} | grep "\$.*=.*@@.*;" > ${VARTMP}
	cat ${SAMPLE} | grep "/// " > ${COMTMP}

	COUNT=$( cat ${VARTMP} | wc -l )

	echo
	echo "Fill in the following information and press enter to confirm:"
	echo

	for LINE in $( seq 1 ${COUNT} ); do

        	DESCRIPTION="$( sed -n ${LINE}p ${COMTMP} | sed 's|/// ||g' )"
        	VAR="$( sed -n ${LINE}p ${VARTMP} )"
		VARONLY="$( echo ${VAR} | sed "s/=.*//g;s/ //g" )"
		echo ${DESCRIPTION}
		read -p "${VARONLY} = "
		VALUE="${REPLY}"
		sed -i "s|\\${VARONLY} =.*|\\${VARONLY} = \"${VALUE}\"\;|g" ${FILE}
		echo
	done

	rm ${COMTMP} ${VARTMP}
	exit 0

elif [ "${CONTINUE}" == "N" ]; then
	echo "Cancelled."
	exit 1
else
	echo "Unrecognized option, cancelling."
	exit 1
fi

