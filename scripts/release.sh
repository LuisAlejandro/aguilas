#!/bin/bash

VERSION="../VERSION"
CHANGELOG="../ChangeLog"
CHANGES="../../CHANGES"
DEVERSION="../../DEVERSION"
NEWCHANGES="../../NEWCHANGES"
DATE=$( date +%D )

git checkout development
git log > ${CHANGES}
cp ${VERSION} ${DEVERSION}
git checkout release

OLDCOMMIT=$( cat ${VERSION} | grep "COMMIT" | sed 's/COMMIT = //g' )
OLDCOMMITLINE=$( cat ${CHANGES}  | grep -n "${OLDCOMMIT}" | awk -F: '{print $1}')
NEWVERSION=$( cat ${DEVERSION} | grep "VERSION" | sed 's/VERSION = //g;s/~.*//g' )

echo "STABLE RELEASE v${NEWVERSION} (${DATE})" > ${NEWCHANGES}
cat ${CHANGES} | sed -n 1,${OLDCOMMITLINE}p | sed 's/commit.*//g;s/Author:.*//g;s/Date:.*//g;s/Merge.*//g;/^$/d;' >> ${NEWCHANGES}
echo "" >> ${NEWCHANGES}
cat ${CHANGELOG} >> ${NEWCHANGES}

git merge development

mv ${NEWCHANGES} ${CHANGELOG}
rm ${CHANGES} ${DEVERSION}

LASTCOMMIT=$( git rev-parse HEAD )

echo "VERSION = ${NEWVERSION}" > ${VERSION}
echo "COMMIT = ${LASTCOMMIT}" >> ${VERSION}

git add .
git commit -a -m "New stable release ${NEWVERSION}"
git tag stable/${NEWVERSION} -m "New stable release ${NEWVERSION}"

git push --tags git@github.com:HuntingBears/aguilas.git release
git push --tags git@gitorious.org:huntingbears/aguilas.git release
git push --tags https://code.google.com/p/aguilas/ release

git archive -o aguilas-${NEWVERSION}.tar.gz v${NEWVERSION}
md5sum aguilas-${NEWVERSION}.tar.gz > aguilas-${NEWVERSION}.tar.gz.md5

python googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION}" -p "aguilas" -l "Type-Archive,Type-Source,OpSys-Linux,Featured,Stable" aguilas-${NEWVERSION}.tar.gz
python googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION} MD5SUM" -p "aguilas" -l "Featured,Stable" aguilas-${NEWVERSION}.tar.gz.md5

mv aguilas-${NEWVERSION}.tar.gz aguilas-${NEWVERSION}.tar.gz.md5 ..

