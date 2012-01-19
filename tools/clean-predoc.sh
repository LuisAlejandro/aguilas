#!/bin/bash

ROOTDIR="$( pwd )"
DOCDIR="${ROOTDIR}/documentation"
OUTFILES=$( grep -R "@@.*@@" ${DOCDIR} | awk -F: '{print $1}' | sort -u | grep "^.*\.in$" | sed 's|\.in$||g' )

for OUTFILE in ${OUTFILES}; do
	rm -rf ${OUTFILE}
done
