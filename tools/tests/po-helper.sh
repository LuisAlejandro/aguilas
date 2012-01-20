#!/bin/bash

CODE="es_VE"
PO="../locale/${CODE}/LC_MESSAGES/messages.po"
POR="../locale/${CODE}/LC_MESSAGES/messages2.po"
MSGID="../locale/${CODE}/LC_MESSAGES/msgid"

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
		STRING="${REPLY}"
		CODESTRING='msgid "'${STRING}'"'

		sed -i "s/msgid \"${SENTENCE}\"/${CODESTRING}\n${TRANSLATION}/g" ${POR}

		FILES=$( grep -R "${SENTENCE}" $(pwd) | grep ".*.php" | awk -F: '{print $1}' )

		for FILE in ${FILES}; do
			sed -i "s/_(\"${SENTENCE}\")/_(\"${STRING}\")/g" ${FILE}
		done
	fi

done

rm ${MSGID}
