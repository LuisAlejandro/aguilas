#!/bin/bash

ROOTDIR="$( pwd )"
GITHUBWIKI="${ROOTDIR}/documentation/githubwiki"
GOOGLBWIKI="${ROOTDIR}/documentation/googlewiki"
VERSION="${ROOTDIR}/VERSION"
CHANGELOG="${ROOTDIR}/ChangeLog"
CHANGES="$( tempfile )"
DEVERSION="$( tempfile )"
NEWCHANGES="$( tempfile )"
DATE=$( date +%D )

if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[MAIN] You are not on \"development\" branch."
	exit 1
fi
if [ "$( git diff --exit-code 2> /dev/null )" != "0" ]; then
	echo "[MAIN] You have uncommitted code on \"development\" branch."
	exit 1
fi
cd ${GITHUBWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[GITHUBWIKI] You are not on \"development\" branch."
	exit 1
fi
if [ "$( git diff --exit-code 2> /dev/null )" != "0" ]; then
	echo "[GITHUBWIKI] You have uncommitted code on \"development\" branch."
	exit 1
fi
cd ${ROOTDIR}
cd ${GOOGLEWIKI}
if [ "$( git branch 2> /dev/null | sed -e '/^[^*]/d;s/\* //' )" != "development" ]; then
	echo "[GOOGLEWIKI] You are not on \"development\" branch."
	exit 1
fi
if [ "$( git diff --exit-code 2> /dev/null )" != "0" ]; then
	echo "[GOOGLEWIKI] You have uncommitted code on \"development\" branch."
	exit 1
fi	
cd ${ROOTDIR}

git log > ${CHANGES}
cp ${VERSION} ${DEVERSION}
git checkout release

OLDCOMMIT=$( cat ${VERSION} | grep "COMMIT" | sed 's/COMMIT = //g' )
OLDCOMMITLINE=$( cat ${CHANGES}  | grep -n "${OLDCOMMIT}" | awk -F: '{print $1}')
NEWVERSION=$( cat ${DEVERSION} | grep "VERSION" | sed 's/VERSION = //g;s/+.*//g' )

echo "STABLE RELEASE v${NEWVERSION} (${DATE})" > ${NEWCHANGES}
cat ${CHANGES} | sed -n 1,${OLDCOMMITLINE}p | sed 's/commit.*//g;s/Author:.*//g;s/Date:.*//g;s/Merge.*//g;/^$/d;' >> ${NEWCHANGES}
sed -i 's/New stable release.*//g' ${NEWCHANGES}
sed -i 's/New development snapshot.*//g' ${NEWCHANGES}
echo "" >> ${NEWCHANGES}
cat ${CHANGELOG} >> ${NEWCHANGES}

git merge development

mv ${NEWCHANGES} ${CHANGELOG}
rm ${CHANGES} ${DEVERSION}

LASTCOMMIT=$( git rev-parse HEAD )

echo "VERSION = ${NEWVERSION}" > ${VERSION}
echo "COMMIT = ${LASTCOMMIT}" >> ${VERSION}

cd ${GOOGLEWIKI}
git checkout master
git merge development
git push --tags https://code.google.com/p/aguilas.wiki/ master
git checkout development
cd ${ROOTDIR}

cd ${GITHUBWIKI}
git checkout master
git merge development
git push --tags git@github.com:HuntingBears/aguilas.wiki.git master
git checkout development
cd ${ROOTDIR}

git add .
git commit -a -m "New stable release ${NEWVERSION}"
git tag ${NEWVERSION} -m "New stable release ${NEWVERSION}"

git push --tags git@github.com:HuntingBears/aguilas.git release
git push --tags git@gitorious.org:huntingbears/aguilas.git release
git push --tags https://code.google.com/p/aguilas/ release

git archive -o aguilas-${NEWVERSION}.tar.gz ${NEWVERSION}
md5sum aguilas-${NEWVERSION}.tar.gz > aguilas-${NEWVERSION}.tar.gz.md5

python -B googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION}" -p "aguilas" -l "Type-Archive,Type-Source,OpSys-Linux,Featured,Stable" aguilas-${NEWVERSION}.tar.gz
python -B googlecode-upload.py -s "AGUILAS RELEASE ${NEWVERSION} MD5SUM" -p "aguilas" -l "Featured,Stable" aguilas-${NEWVERSION}.tar.gz.md5

mv aguilas-${NEWVERSION}.tar.gz aguilas-${NEWVERSION}.tar.gz.md5 ..

git checkout development
