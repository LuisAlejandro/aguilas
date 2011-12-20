#!/bin/bash

SOURCESDIR=".."
TMP="tmp"

grep -R "^.*_(\".*\").*$" ${SOURCESDIR} > ${TMP}

COUNT=$( cat ${TMP} | wc -l )

for LINE in $( seq 1 ${COUNT} ); do

	RESULT=$( sed -n ${LINE}p ${TMP} | awk -F: '{print $1}' ) 


done



