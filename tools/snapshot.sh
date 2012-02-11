#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/snapshot.sh
# DESCRIPTION:  Makes a new development snapshot of Aguilas.
# USAGE: ./tools/snapshot.sh
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
GITHUBWIKI="${ROOTDIR}/documentation/githubwiki"
GOOGLEWIKI="${ROOTDIR}/documentation/googlewiki"
VERSION="${ROOTDIR}/VERSION"
CHANGELOG="${ROOTDIR}/ChangeLog"
CHANGES="$( tempfile )"
NEWCHANGES="$( tempfile )"
DATE=$( date +%D )
SNAPSHOT=$( date +%Y%m%d%H%M%S )
VERDE="\e[1;32m"
ROJO="\e[1;31m"
AMARILLO="\e[1;33m"
FIN="\e[0m"

function ERROR() {
echo -e ${ROJO}${1}${FIN}
}

function WARNING() {
echo -e ${AMARILLO}${1}${FIN}
}

function SUCCESS() {
echo -e ${VERDE}${1}${FIN}
}

git config --global user.name "Luis Alejandro Martínez Faneyth"
git config --global user.email "luis@huntingbears.com.ve"
export DEBFULLNAME="Luis Alejandro Martínez Faneyth"
export DEBEMAIL="luis@huntingbears.com.ve"

if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	ERROR "[MAIN] You are not on \"development\" branch."
	git checkout development
fi
cd ${GITHUBWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	ERROR "[GITHUBWIKI] You are not on \"development\" branch."
	git checkout development
fi
cd ${ROOTDIR}
cd ${GOOGLEWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	ERROR "[GOOGLEWIKI] You are not on \"development\" branch."
	git checkout development
fi
cd ${ROOTDIR}

WARNING "Updating Google Code wiki ..."
cd ${GOOGLEWIKI}
git add .
git commit -q -a -m "Updating documentation"
git push -q --tags https://code.google.com/p/aguilas.wiki/ development
cd ${ROOTDIR}

WARNING "Updating Github wiki ..."
cd ${GITHUBWIKI}
git add .
git commit -q -a -m "Updating documentation"
git push -q --tags git@github.com:HuntingBears/aguilas.wiki.git development
cd ${ROOTDIR}

WARNING "Committing changes ..."
git add .
git commit -a

if [ $? == 1 ]; then
	ERROR "Empty commit message, aborting."
	exit 1
fi

git log > ${CHANGES}

OLDVERSION=$( cat ${VERSION} | grep "VERSION" | sed 's/VERSION = //g' )
OLDCOMMIT=$( cat ${VERSION} | grep "COMMIT" | sed 's/COMMIT = //g' )
OLDCOMMITLINE=$( cat ${CHANGES}  | grep -n "${OLDCOMMIT}" | awk -F: '{print $1}' )

read -p "Enter new version (last version was ${OLDVERSION}): "
NEWVERSION="${REPLY}"

echo "DEVELOPMENT RELEASE v${NEWVERSION}+${SNAPSHOT} (${DATE})" > ${NEWCHANGES}
cat ${CHANGES} | sed -n 1,${OLDCOMMITLINE}p | sed 's/commit.*//g;s/Author:.*//g;s/Date:.*//g;s/Merge.*//g;/^$/d;' >> ${NEWCHANGES}
sed -i 's/New development snapshot.*//g' ${NEWCHANGES}
echo "" >> ${NEWCHANGES}
cat ${CHANGELOG} >> ${NEWCHANGES}
mv ${NEWCHANGES} ${CHANGELOG}
rm ${CHANGES}

LASTCOMMIT=$( git rev-parse HEAD )

echo "VERSION = ${NEWVERSION}+${SNAPSHOT}" > ${VERSION}
echo "COMMIT = ${LASTCOMMIT}" >> ${VERSION}

WARNING "Commiting changes ..."
git add .
git commit -q -a -m "New development snapshot ${NEWVERSION}+${SNAPSHOT}"
git tag ${NEWVERSION}+${SNAPSHOT} -m "New development snapshot ${NEWVERSION}+${SNAPSHOT}"
 
WARNING "Creating tarball ..."
tar -czf ../aguilas_${NEWVERSION}+${SNAPSHOT}.orig.tar.gz *

WARNING "Pushing changes to remote repositories ..."
git push -q --tags git@github.com:HuntingBears/aguilas.git development
git push -q --tags git@gitorious.org:huntingbears/aguilas.git development
git push -q --tags https://code.google.com/p/aguilas/ development

SUCCESS "Snapshot Published"
