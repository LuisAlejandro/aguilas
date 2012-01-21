#!/bin/bash

SOURCE="messages.po"
FILES=$( find ../.. -name *.php )

LINEID=$( cat ${SOURCE} | grep -n 'msgid "' | awk -F: '{print $1}' )

for ID in ${LINEID}; do
	for FILE in ${FILES}; do
		STR=$[ ${ID}+1 ]
		SENTENCE1=$( sed -n ${ID}p ${SOURCE} | sed 's/msgid "//g;s/"//' )
		SENTENCE2=$( sed -n ${STR}p ${SOURCE} | sed 's/msgstr "//g;s/"//' )
		sed -i "s|_(\"${SENTENCE1}\")|_(\"${SENTENCE2}\")|g" ${FILE}
		echo "$FILE - $SENTENCE1 - $SENTENCE2"
	done
done
