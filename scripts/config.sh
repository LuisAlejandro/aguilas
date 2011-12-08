#!/bin/bash

SAMPLE="../config.example.php"
FILE="../config.php"
TMP="../options"

cp ${SAMPLE} ${FILE}
cat ${SAMPLE} | grep "\$.*=.*;" > ${TMP}

COUNT=$( cat ${TMP} | wc -l )

for LINE in $( seq 1 ${COUNT} ); do

	SENTENCE=$( sed -n ${LINE}p ${MSGID} | sed 's/msgid //g;s/"//g' )

	if [ -n "${SENTENCE}" ]; then

		read -p "Traduce la siguiente oración: \"${SENTENCE}\" = "
		TRANSLATION='msgstr "'${REPLY}'"'

		read -p "Escribe el código: "
		CODESTRING='msgid "##'${REPLY}'##"'

		sed -i "s/msgid \"${SENTENCE}\"/${CODESTRING}\n${TRANSLATION}/g" ${POR}

		FILES=$( grep -R "${SENTENCE}" $(pwd) | grep ".*.php" | awk -F: '{print $1}' )

		for FILE in ${FILES}; do
			sed -i "s/_(\"${SENTENCE}\")/_(\"${CODESTRING}\")/g" ${FILE}
		done
	fi

done
