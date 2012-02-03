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

git checkout master

git import-orig -v -u${NEWVERSION} --upstream-branch=release \
		--debian-branch=master ../aguilas_${NEWVERSION}.orig.tar.gz

git dch --release --auto --id-length=7 --full
git dch --snapshot --auto --id-length=7 --full

git add .
git commit -a

git buildpackage -kE78DAA2E -tc --git-tag --git-retag
git clean -fd
git reset --hard

git push --tags git@github.com:HuntingBears/aguilas.git master
git push --tags git@gitorious.org:huntingbears/aguilas.git master
git push --tags https://code.google.com/p/aguilas/ master

git checkout development
