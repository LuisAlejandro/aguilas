#!/bin/bash

VERSION="../VERSION"
CHANGELOG="../ChangeLog"
CHANGES="../CHANGES"
NEWCHANGES="../NEWCHANGES"
DATE=$( date +%D )
SNAPSHOT=$( date +%Y%m%d%H%M%S )

git checkout development

git add .
git commit -a
git log > ${CHANGES}

OLDVERSION=$( cat ${VERSION} | grep "VERSION" | sed 's/VERSION = //g' )
OLDCOMMIT=$( cat ${VERSION} | grep "COMMIT" | sed 's/COMMIT = //g' )
OLDCOMMITLINE=$( cat ${CHANGES}  | grep -n "${OLDCOMMIT}" | awk -F: '{print $1}')

read -p "Enter new version (last version was ${OLDVERSION}): "
NEWVERSION="${REPLY}"

echo "DEVELOPMENT RELEASE v${NEWVERSION}+${SNAPSHOT} (${DATE})" > ${NEWCHANGES}
cat ${CHANGES} | sed -n 1,${OLDCOMMITLINE}p | sed 's/commit.*//g;s/Author:.*//g;s/Date:.*//g;s/Merge.*//g;/^$/d;' >> ${NEWCHANGES}
echo "" >> ${NEWCHANGES}
cat ${CHANGELOG} >> ${NEWCHANGES}
mv ${NEWCHANGES} ${CHANGELOG}
rm ${CHANGES}

LASTCOMMIT=$( git rev-parse HEAD )

echo "VERSION = ${NEWVERSION}+${SNAPSHOT}" > ${VERSION}
echo "COMMIT = ${LASTCOMMIT}" >> ${VERSION}

git add .
git commit -a -m "New development release ${NEWVERSION}+${SNAPSHOT}"
git tag development/${NEWVERSION}+${SNAPSHOT} -m "New development release ${NEWVERSION}+${SNAPSHOT}"

git push --tags git@github.com:HuntingBears/aguilas.git development
git push --tags git@gitorious.org:huntingbears/aguilas.git development
git push --tags https://code.google.com/p/aguilas/ development

