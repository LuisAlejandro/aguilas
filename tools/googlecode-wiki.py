#!/usr/bin/env python
# -*- coding: utf-8 -*-
#
# ====================================================================
# PACKAGE: aguilas
# FILE: tools/googlecode-wiki.py
# DESCRIPTION:	Converts all REST sources in documentation/rest/ to
#		Google Code Wiki format using the wikir module.
# USAGE: ./tools/googlecode-wiki.py
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

import os
import fnmatch
from wikir import publish_string

files = os.listdir('documentation/rest/')

for rstpath in files:
    rstpath = 'documentation/rest/'+rstpath
    rstfilename = os.path.basename(rstpath)
    if os.path.isfile(rstpath) and fnmatch.fnmatch(rstpath, '*.rest'):
        rstfile = open(rstpath, 'r')
        rstcontent = rstfile.read()
        print 'Converting: '+rstpath
        wikicontent = publish_string(rstcontent)
        wikipath = 'documentation/googlewiki/'+rstfilename.split('.')[0]+'.wiki'
        wikifile = open(wikipath, 'w')
        wikiput = wikifile.write(wikicontent)
        wikifile.close()
        rstfile.close()

