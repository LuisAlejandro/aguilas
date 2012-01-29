#!/bin/bash
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/maint/mysql-debugging.sh
# DESCRIPTION:  Prints debugging information for a MYSQL server
# USAGE: ./mysql-debugging.sh [ADMIN] [PASS] [HOST]
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
MYSQLADMIN="${1}"
MYSQLPASS="${2}"
MYSQLHOST="${3}"

mysqlreport --user ${MYSQLADMIN} --password ${MYSQLPASS} --host ${MYSQLHOST} | tee ${TMP}

echo
echo "Log saved at ${TMP}"
echo
