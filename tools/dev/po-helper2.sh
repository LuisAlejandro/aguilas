#!/bin/bash

PO="../locale/en_US/LC_MESSAGES/messages.po"
POR="../locale/es_VE/LC_MESSAGES/messages.po"
POR2="../locale/es_VE/LC_MESSAGES/messages2.po"

COUNT=$( cat ${PO} | grep -n 'msgstr "' | awk -F: '{print $1}' )

cp ${PO} ${POR2}

for LINE in ${COUNT}; do

	SENTENCE1=$( sed -n ${LINE}p ${PO} )
	SENTENCE2=$( sed -n ${LINE}p ${POR} )
	sed -i "s/${SENTENCE1}/${SENTENCE2}/g" ${POR2}

done
