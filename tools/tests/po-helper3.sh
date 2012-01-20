#!/bin/bash

SOURCE="../../po/en_US/LC_MESSAGES/aguilas.po"
INPUT="../../po/es_VE/LC_MESSAGES/aguilas.po"

LINEID=$( cat ${SOURCE} | grep -n 'msgid "' | awk -F: '{print $1}' )

cp ${INPUT} ${MODIFY}

for ID in ${LINEID}; do
	STR=$[ ${ID}+1 ]
	SENTENCE1=$( sed -n ${ID}p ${SOURCE} )
	SENTENCE2=$( sed -n ${STR}p ${SOURCE} | sed 's/msgstr "/msgid "/g' )
	sed -i "s/${SENTENCE1}/${SENTENCE2}/g" ${MODIFY}
done
