#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/maint/l10n-chmsgid-global.sh
# DESCRIPTION:  Changes each MSGID string from all sources and POT
#		template if the user provides an alternative.
# USAGE: ./tools/maint/l10n-chmsgid-global.sh
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

ROOTDIR="$( pwd )"
POT="${ROOTDIR}/locale/pot/aguilas/messages.pot"
TMP="$( tempfile )"
FILES=$( find ../.. -name *.php )

cp ${POT} ${TMP}

LINEID=$( cat ${TMP} | grep -n 'msgid "' | grep -v 'msgid ""' | awk -F: '{print $1}' )

for ID in ${LINEID}; do
	SENTENCE=$( sed -n ${ID}p ${TMP} | sed 's/msgid //g;s/"//g' )

	read -p "Previous: \"${SENTENCE}\"; New = "

	NEW='msgid "'${REPLY}'"'
        OLD='msgid "'${SENTENCE}'"'

	if [ "${NEW}" != 'msgid ""' ]; then
		sed -i "s|${OLD}|${NEW}|g" ${TMP}
		for FILE in ${FILES}; do
			sed -i "s|_(\"${OLD}\")|_(\"${NEW}\")|g" ${FILE}
		done
	fi
done

cp ${TMP} ${POT}

echo
echo "FINISHED!"
echo
