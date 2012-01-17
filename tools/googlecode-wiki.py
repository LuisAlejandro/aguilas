import os
import fnmatch
from wikir import publish_string

files = os.listdir('documentation/rest/')

for rstpath in files:
    if os.path.isfile(rstpath) and fnmatch.fnmatch(rstpath, '*.rest'):
        rstfile = open(rstpath, 'r')
        rstcontent = rstfile.read()
        wikicontent = publish_string(rstcontent)
        wikipath = 'documentation/googlewiki/'+rstpath.split('.')[0]+'.wiki'
        wikifile = open(wikipath, 'w')
        wikiput = wikifile.write(wikicontent)
        wikifile.close()
        rstfile.close()

