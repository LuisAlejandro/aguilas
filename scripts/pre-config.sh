#!/bin/bash

SAMPLE="../config.php.example"
FILE="../config.php"
VARTMP="../var"
COMTMP="../com"

echo
echo "We are going to ask you a couple of questions regarding AGUILAS configuration."
echo "If you have not yet defined your LDAP/MYSQL server,"
echo "cancel the process and check the INSTALL file for more instructions."

echo
echo "Are you ready to configure AGUILAS? Press Y to continue or N to cancel."

read -p "[Y/N]"
CONTINUE="${REPLY}"

if [ "${CONTINUE}" == "Y" ]; then
	
	cp ${SAMPLE} ${FILE}
	cat ${SAMPLE} | grep "\$.*=.*@@.*;" > ${VARTMP}
	cat ${SAMPLE} | grep "/// " > ${COMTMP}

	COUNT=$( cat ${VARTMP} | wc -l )

	echo
	echo "Fill in the following information and press enter to confirm:"
	echo

	for LINE in $( seq 1 ${COUNT} ); do

        	DESCRIPTION="$( sed -n ${LINE}p ${COMTMP} | sed 's|/// ||g' )"
        	VAR="$( sed -n ${LINE}p ${VARTMP} )"
		VARONLY="$( echo ${VAR} | sed "s/=.*//g;s/ //g" )"
		echo ${DESCRIPTION}
		read -p "${VARONLY} = "
		VALUE="${REPLY}"
		sed -i 's/\'${VARONLY}'.*/\'${VARONLY}' = "'${VALUE}'";/g' ${FILE}
		echo
	done

	rm ${COMTMP} ${VARTMP}
	exit 0

elif [ "${CONTINUE}" == "N" ]; then
	echo "Cancelled."
	exit 1
else
	echo "Unrecognized option, cancelling."
	exit 1
fi

