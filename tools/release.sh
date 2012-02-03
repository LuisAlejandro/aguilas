#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/release.sh
# DESCRIPTION:  Makes a new stable release of Aguilas.
# USAGE: ./tools/release.sh
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
GOOGLBWIKI="${ROOTDIR}/documentation/googlewiki"
VERSION="${ROOTDIR}/VERSION"
CHANGELOG="${ROOTDIR}/ChangeLog"
CHANGES="$( tempfile )"
DEVERSION="$( tempfile )"
NEWCHANGES="$( tempfile )"
DATE=$( date +%D )

git log > ${CHANGES}
cp ${VERSION} ${DEVERSION}
git checkout release

OLDCOMMIT="$( cat ${VERSION} | grep "COMMIT" | sed 's/COMMIT = //g' )"
OLDCOMMITLINE="$( cat ${CHANGES}  | grep -n "${OLDCOMMIT}" | awk -F: '{print $1}' )"
NEWVERSION="$( cat ${DEVERSION} | grep "VERSION" | sed 's/VERSION = //g;s/+.*//g' )"

echo "STABLE RELEASE v${NEWVERSION} (${DATE})" > ${NEWCHANGES}
cat ${CHANGES} | sed -n 1,${OLDCOMMITLINE}p | sed 's/commit.*//g;s/Author:.*//g;s/Date:.*//g;s/Merge.*//g;/^$/d;' >> ${NEWCHANGES}
sed -i 's/New stable release.*//g' ${NEWCHANGES}
sed -i 's/New development snapshot.*//g' ${NEWCHANGES}
echo "" >> ${NEWCHANGES}
cat ${CHANGELOG} >> ${NEWCHANGES}

git merge -s recursive -X theirs development

mv ${NEWCHANGES} ${CHANGELOG}
rm ${CHANGES} ${DEVERSION}

LASTCOMMIT=$( git rev-parse HEAD )

echo "VERSION = ${NEWVERSION}" > ${VERSION}
echo "COMMIT = ${LASTCOMMIT}" >> ${VERSION}

cd ${GOOGLEWIKI}
git checkout master
git merge -s recursive -X theirs development
git push --tags https://code.google.com/p/aguilas.wiki/ master
git checkout development
cd ${ROOTDIR}

cd ${GITHUBWIKI}
git checkout master
git merge -s recursive -X theirs development
git push --tags git@github.com:HuntingBears/aguilas.wiki.git master
git checkout development
cd ${ROOTDIR}

git checkout release

git add .
git commit -a -m "New stable release ${NEWVERSION}"
git tag ${NEWVERSION} -m "New stable release ${NEWVERSION}"

git push --tags git@github.com:HuntingBears/aguilas.git release
git push --tags git@gitorious.org:huntingbears/aguilas.git release
git push --tags https://code.google.com/p/aguilas/ release

git archive -o aguilas_${NEWVERSION}.orig.tar.gz ${NEWVERSION}
md5sum aguilas_${NEWVERSION}.orig.tar.gz > aguilas_${NEWVERSION}.orig.tar.gz.md5

python -B googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION}" \
	-p "aguilas" -l "Type-Archive,Type-Source,OpSys-Linux,Featured,Stable" \
	aguilas_${NEWVERSION}.orig.tar.gz
python -B googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION} MD5SUM" \
	-p "aguilas" -l "Featured,Stable" aguilas_${NEWVERSION}.orig.tar.gz.md5

mv aguilas*.tar.gz* ..

git checkout development
