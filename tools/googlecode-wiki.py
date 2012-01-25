import os
import fnmatch
from wikir import publish_string

files = os.listdir('documentation/rest/')

for rstpath in files:
    rstpath = 'documentation/rest/'+rstpath
    rstfilename = os.path.basename(rstpath)
    if os.path.isfile(rstpath) and fnmatch.fnmatch(rstpath, '*.rest'):
        rstfile = open(rstpath, 'r')
        rstcontent = rstfile.read()
        print 'Converting: '+rstpath
        wikicontent = publish_string(rstcontent)
        wikipath = 'documentation/googlewiki/'+rstfilename.split('.')[0]+'.wiki'
        wikifile = open(wikipath, 'w')
        wikiput = wikifile.write(wikicontent)
        wikifile.close()
        rstfile.close()

