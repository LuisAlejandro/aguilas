#!/bin/bash

SOURCESDIR=".."


echo '' > messages.po
find ${SOURCESDIR} -type f -iname "*.php" | xgettext --keyword=_ -j -f -
msgmerge -N existing.po messages.po > new.po
mv new.po existing.po
rm messages.po

