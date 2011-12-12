#!/bin/bash

CODE="es_VE"
PO="../locale/${CODE}/LS_MESSAGES/messages.po"
POR="../locale/${CODE}/LS_MESSAGES/messages2.po"
MSGID="../locale/${CODE}/LS_MESSAGES/msgid"

cat ${PO} | grep 'msgid "' > ${MSGID}
COUNT=$( cat ${MSGID} | wc -l )

cp ${PO} ${POR}
sed -i 's/msgstr ""//g' ${POR}

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
