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

if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[MAIN] You are not on \"development\" branch."
	exit 1
fi
cd ${GITHUBWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[GITHUBWIKI] You are not on \"development\" branch."
	exit 1
fi
cd ${ROOTDIR}
cd ${GOOGLEWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[GOOGLEWIKI] You are not on \"development\" branch."
	exit 1
fi
cd ${ROOTDIR}

cd ${GOOGLEWIKI}
git add .
git commit -a -m "Updating documentation"
git push --tags https://code.google.com/p/aguilas.wiki/ development
cd ${ROOTDIR}

cd ${GITHUBWIKI}
git add .
git commit -a -m "Updating documentation"
git push --tags git@github.com:HuntingBears/aguilas.wiki.git development
cd ${ROOTDIR}

git add .
git commit -a

if [ $? == 1 ]; then
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

git add .
git commit -a -m "New development snapshot ${NEWVERSION}+${SNAPSHOT}"
git tag ${NEWVERSION}+${SNAPSHOT} -m "New development snapshot ${NEWVERSION}+${SNAPSHOT}"

git push --tags git@github.com:HuntingBears/aguilas.git development
git push --tags git@gitorious.org:huntingbears/aguilas.git development
git push --tags https://code.google.com/p/aguilas/ development

