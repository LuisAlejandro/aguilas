#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/maint/l10n-newpo.sh
# DESCRIPTION:	Generates a PO file for a new translated language,
#		based on the Aguilas POT template.
# USAGE: ./tools/maint/l10n-newpo.sh [L10N CODE]
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

CODE="${1}"
ROOTDIR="$( pwd )"
POT="${ROOTDIR}/locale/pot/aguilas/messages.pot"
PO="${ROOTDIR}/locale/${CODE}/LC_MESSAGES/messages.po"
PODIR="$( dirname ${PO} )"
MSGID="$( tempfile )"

echo
echo "This procedure is going to overwrite any existing file in locale/${CODE}/LC_MESSAGES/messages.po"
echo
echo "Do you want to continue? Press Y to continue or N to cancel."

read -p "[Y/N]"
CONTINUE="${REPLY}"

if [ "${CONTINUE}" == "Y" ]; then

	mkdir -p ${PODIR}
	cp ${POT} ${PO}
	cat ${PO} | grep 'msgid "' > ${MSGID}
	sed -i 's/msgstr ""//g' ${PO}

	COUNT=$( cat ${MSGID} | wc -l )

	for LINE in $( seq 1 ${COUNT} ); do

		SENTENCE=$( sed -n ${LINE}p ${MSGID} | sed 's/msgid //g;s/"//g' )

		if [ -n "${SENTENCE}" ]; then

			read -p "Translate the following sentence: \"${SENTENCE}\" = "
			TRANSLATION='msgstr "'${REPLY}'"'
			CODESTRING='msgid "'${SENTENCE}'"'

			sed -i "s/msgid \"${SENTENCE}\"/${CODESTRING}\n${TRANSLATION}/g" ${PO}
		fi
	done

	rm ${MSGID}

	echo
	echo "FINISHED!"
	echo
fi
