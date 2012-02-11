#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/buildpackage.sh
# DESCRIPTION:  Makes a new debian package of a stable release.
# USAGE: ./tools/buildpackage.sh
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
ROOTNAME="$( basename ${ROOTDIR} )"
PROJDIR="$( dirname ${ROOTDIR} )"
VERSION="${ROOTDIR}/VERSION"
TYPE="${1}"
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

git checkout master
git clean -fd
git reset --hard

LASTCOMMIT="$( git rev-parse HEAD )"
OLDDEBVERSION="$( dpkg-parsechangelog | grep "Version: " | awk '{print $2}' )"
OLDDEBSTATUS="$( dpkg-parsechangelog | grep "Distribution: " | awk '{print $2}' )"
OLDRELVERSION="$( echo ${OLDDEBVERSION} | sed 's/-.*//g' )"
OLDREV="$( echo ${OLDDEBVERSION#${OLDRELVERSION}-} | sed 's/~.*//g' )"

WARNING "Merging new upstream release ..."

if [ "${TYPE}" == "final-release" ] || [ "${TYPE}" == "test-release" ]; then
	git merge -q -s recursive -X theirs --squash release
elif [ "${TYPE}" == "test-snapshot" ]; then
	git merge -q -s recursive -X theirs --squash development
fi

NEWRELVERSION="$( cat ${VERSION} | grep "VERSION" | sed 's/VERSION = //g' )"

if [ "${OLDRELVERSION}" == "${NEWRELVERSION}" ]; then
	if [ "${OLDDEBSTATUS}" == "UNRELEASED" ]; then
		NEWREV="${OLDREV}"
	else
		NEWREV="$[ ${OLDREV}+1 ]"
	fi
else
	NEWREV="1"
fi

NEWDEBVERSION="${NEWRELVERSION}-${NEWREV}"

WARNING "Generating Debian changelog ..."
if [ "${TYPE}" == "final-release" ]; then
	OPTIONS="-kE78DAA2E -tc --git-tag --git-retag"
	git dch --new-version="${NEWDEBVERSION}" --release --auto --id-length=7 --full
elif [ "${TYPE}" == "test-snapshot" ] || [ "${TYPE}" == "test-release" ]; then
	OPTIONS="-us -uc"
	git dch --new-version="${NEWDEBVERSION}" --snapshot --auto --id-length=7 --full
fi

WARNING "Committing changes ..."
git add .
git commit -q -a -m "Importing New Upstream Release (${NEWRELVERSION})"

WARNING "Generating tarball ..."
tar --anchored --exclude="debian" -czf ../aguilas_${NEWRELVERSION}.orig.tar.gz *

WARNING "Generating Debian package ..."
git buildpackage ${OPTIONS}
git clean -fd
git reset --hard

if [ "${TYPE}" == "final-release" ]; then
	WARNING "Uploading changes to remote servers ..."
	git push -q --tags git@github.com:HuntingBears/aguilas.git master
	git push -q --tags git@gitorious.org:huntingbears/aguilas.git master
	git push -q --tags https://code.google.com/p/aguilas/ master

	WARNING "Uploading Debian package to Google Code ..."
	python -B tools/googlecode-upload.py -s "Aguilas debian package [${NEWDEBVERSION}]" -p "aguilas" -l "Featured,Type-Package,Type-Installer,OpSys-Linux,Stable" ../aguilas_${NEWDEBVERSION}_all.deb
	python -B tools/googlecode-upload.py -s "Aguilas debian source (dsc) [${NEWDEBVERSION}]" -p "aguilas" -l "Type-Source,OpSys-Linux,Stable" ../aguilas_${NEWDEBVERSION}.dsc
	python -B tools/googlecode-upload.py -s "Aguilas debian source (debian.tar.gz) [${NEWDEBVERSION}]" -p "aguilas" -l "Type-Archive,Type-Source,OpSys-Linux,Stable" ../aguilas_${NEWDEBVERSION}.debian.tar.gz
	python -B tools/googlecode-upload.py -s "Aguilas upstream source (orig.tar.gz) [${NEWRELVERSION}]" -p "aguilas" -l "Featured,Type-Archive,Type-Source,OpSys-Linux,Stable" ../aguilas_${NEWRELVERSION}.orig.tar.gz
fi

if [ "${TYPE}" != "final-release" ]; then
	git reset --hard ${LASTCOMMIT}
	git clean -fd
fi

git checkout development

exit 0
