Changelog
=========

0.1.10 (2017-05-19)
-------------------

Changes
~~~~~~~

- [REF] Minor documentation changes. [Luis Alejandro Martínez Faneyth]

- [REF] Modifying parsing of URL to fix download errrors. [Luis
  Alejandro Martínez Faneyth]

- [REF] Fixing python 2.6 support. [Luis Alejandro Martínez Faneyth]

0.1.9 (2017-05-18)
------------------

Changes
~~~~~~~

- [REF] Improving memory management. [Luis Alejandro Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.8 (2017-05-18)
------------------

Changes
~~~~~~~

- [REF] Extending available memory limit to 600MB. [Luis Alejandro
  Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.7 (2017-05-18)
------------------

Changes
~~~~~~~

- [REF] Adding check to avoid running out of memory. Fixes #11. [Luis
  Alejandro Martínez Faneyth]

Fix
~~~

- [FIX] Fixing memory usage calculation. Fixes #10. [Luis Alejandro
  Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.6 (2017-05-13)
------------------

Changes
~~~~~~~

- [REF] Adding Maintainer guide and changing landscape.io for Code
  Climate. [Luis Alejandro Martínez Faneyth]

- [REF] Refactoring `pypicontents pypi` to allow the reading of .whl and
  .egg formats. [Luis Alejandro Martínez Faneyth]

- [REF] Changing location of pip cache. [Luis Alejandro Martínez
  Faneyth]

- [REF] Adding minimal test. [Luis Alejandro Martínez Faneyth]

- [REF] Only try to download a file once (closes #7). [REF] Refactoring
  pypicontents.wrapper to be better organized. [REF] Updating regexes in
  pypicontents.api.errors and pypicontents.api.stats to match new
  strings. [REF] Allowing exception logs to show in
  pypicontents.api.pypi. [REF] Removing the portion of code that removes
  directories from pip cache. Let the user remove them at will. [REF]
  Moving inspection of setup.py to pypicontents.wrapper to isolate
  better the importing of foreign modules. [Luis Alejandro Martínez
  Faneyth]

- [REF] Monkeypatching logging._levelNames for python >= 3.4 (closes
  #9). [Luis Alejandro Martínez Faneyth]

Fix
~~~

- [FIX] Fixing error strings. [REF] Refactoring functional tests. [Luis
  Alejandro Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

- Fixing python 3.2 incompatibility. Adding functional tests with
  docker. Updating module level documentation. Removing xmlrpc api
  because json api is enough. Adding support for whl and egg archive
  extensions. Removing unused code, unused functions and general
  linting. [Luis Alejandro Martínez Faneyth]

0.1.5 (2017-01-05)
------------------

Fix
~~~

- [FIX] Fixing logger behaviour in python 2.6 and adding case for
  inventory v1 in the stdlib command. [Luis Alejandro Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.4 (2017-01-05)
------------------

Fix
~~~

- [FIX] Hotfix to fix python 2.6 support. [Luis Alejandro Martínez
  Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.3 (2017-01-04)
------------------

Fix
~~~

- [FIX] Adding coding to commands to avoid encoding issues. [Luis
  Alejandro Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.2 (2017-01-04)
------------------

New
~~~

- [ADD] Adding configuration file for gitchangelog. [Luis Alejandro
  Martínez Faneyth]

Changes
~~~~~~~

- [REF] Updating year in copyright. [Luis Alejandro Martínez Faneyth]

- [REF] Adding support for python 2.6. [Luis Alejandro Martínez Faneyth]

- [REF] Removing dependency on `sphinx` (closes #6). By importing
  `fetch_inventory` from `sphinx.ext.intersphinx`, we remove the
  dependency on sphinx and will be able to modify to add compatibility
  with python 3.2. [Luis Alejandro Martínez Faneyth]

- [REF] Updating documentation. [Luis Alejandro Martínez Faneyth]

- [REF] Improving maintainer info. [Luis Alejandro Martínez Faneyth]

Fix
~~~

- [FIX] Fixing errors reported by flake8. [Luis Alejandro Martínez
  Faneyth]

- [FIX] Fixing stdlib errors (closes #5). Various errors in different
  python versions fixed. [Luis Alejandro Martínez Faneyth]

- [FIX] Removing fixed versions of python interpreters and replacing for
  dynamic discovery. [FIX] Only killing Popen if is running. [FIX]
  setupdir was misplaced. [REF] Improving documentation. [Luis Alejandro
  Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

- Removing branch CI restrictions. [Luis Alejandro Martínez Faneyth]

0.1.1 (2016-12-19)
------------------

Changes
~~~~~~~

- [REF] Updating documentation. [Luis Alejandro Martínez Faneyth]

Fix
~~~

- [FIX] Removing fixed versions of python interpreters and replacing for
  dynamic discovery. [FIX] Only killing Popen if is running. [FIX]
  setupdir was misplaced. [REF] Improving documentation. [Luis Alejandro
  Martínez Faneyth]

Other
~~~~~

- Updating Changelog and version. [Luis Alejandro Martínez Faneyth]

0.1.0 (2016-12-19)
------------------

New
~~~

- [ADD] Adding project's metadata. [Luis Alejandro Martínez Faneyth]

- [ADD] .travis.yml: Configuring the generation of the json file in the
  script section. [ADD] process.py: First version of the script. [Luis
  Alejandro Martínez Faneyth]

Changes
~~~~~~~

- [REF] Improving docs. [Luis Alejandro Martínez Faneyth]

- [REF] Commiting changelog. [Luis Alejandro Martínez Faneyth]

- [REF] Improving docs. [Luis Alejandro Martínez Faneyth]

- [REF] Adding maintainer info. [Luis Alejandro Martínez Faneyth]

- [REF] Improving documentation. [REF] Refactoring commands. [Luis
  Alejandro Martínez Faneyth]

- [REF] Improving documentation. [REF] Improving commandline parser.
  [Luis Alejandro Martínez Faneyth]

- [REF] Renaming commands. [REF] Improving documentation. [Luis
  Alejandro Martínez Faneyth]

- [REF] Improving documentation. [Luis Alejandro Martínez Faneyth]

- [REF] Restrict branches to build on Travis. [Luis Alejandro Martínez
  Faneyth]

- [REF] Improving README. [Luis Alejandro Martínez Faneyth]

- [REF] Updating graphic image. [Luis Alejandro Martínez Faneyth]

- [REF] Removing unnecessary code. [Luis Alejandro Martínez Faneyth]

- [REF] Deprecating python 3.3 in favor of python 3.6. [Luis Alejandro
  Martínez Faneyth]

- [REF] Integrating script contents to .travis.yml. [Luis Alejandro
  Martínez Faneyth]

- [REF] Moving Dockerfiles to LuisAlejandro/dockerfiles. [Luis Alejandro
  Martínez Faneyth]

- [REF] Stablishing limits. [Luis Alejandro Martínez Faneyth]

- [REF] Implementing stdlib population in this branch. [Luis Alejandro
  Martínez Faneyth]

- [REF] Adding inspection functions for when the setup.py file cannot be
  executed. [REF] Adding case for when a download release is nor found,
  search in download_url. [Luis Alejandro Martínez Faneyth]

- [REF] Restricting build branches. [Luis Alejandro Martínez Faneyth]

- [REF] Changing json name to pypi. [Luis Alejandro Martínez Faneyth]

- [REF] Updating secure keys. [Luis Alejandro Martínez Faneyth]

- [REF] Passing time measuring to python process. [Luis Alejandro
  Martínez Faneyth]

- [REF] Correcting code style. [FIX] Fixing typo in README. [REF] Adding
  summary report. [Luis Alejandro Martínez Faneyth]

- [REF] Improving exceptions. [Luis Alejandro Martínez Faneyth]

- [REF] Enabling logging by file. [Luis Alejandro Martínez Faneyth]

- [REF] Refactoring to correct download url. [FIX] Fixing problem with
  variable. [REF] Adding timeout to max 40min to allow push from Travis.
  [Luis Alejandro Martínez Faneyth]

- [REF] Improving commit from Travis. [Luis Alejandro Martínez Faneyth]

- [REF] Adding more complete .gitignore. [Luis Alejandro Martínez
  Faneyth]

- [REF] Refactoring the parse of entry_points. [Luis Alejandro Martínez
  Faneyth]

- [REF] Refactoring setupargs. [ADD] Adding logs. [Luis Alejandro
  Martínez Faneyth]

- [REF] Refactoring import procedure to cover more failing packages.
  [Luis Alejandro Martínez Faneyth]

- [REF] Introducing a wrapper script to be able to execute setup with
  different python versions. [Luis Alejandro Martínez Faneyth]

- [REF] Implementing a better __import__ replacement. [Luis Alejandro
  Martínez Faneyth]

- [REF] Implementing a better module mocking. [Luis Alejandro Martínez
  Faneyth]

- [REF] Implementing a false module patch. [Luis Alejandro Martínez
  Faneyth]

- [REF] Refactoring globals overwriting. [Luis Alejandro Martínez
  Faneyth]

- [REF] Refactoring thread execution and overwriting modules through
  exec's globals. [Luis Alejandro Martínez Faneyth]

- [REF] Remove package number limit. [Luis Alejandro Martínez Faneyth]

- [REF] Implementing JSON API instead of XMLRPC because the latter
  complains about ssl stuff with too much requests. [Luis Alejandro
  Martínez Faneyth]

- [REF] General refactoring. Creating a package for better organization
  of code. [FIX] Filling pypicontents.json with preliminar data. [IMP]
  Monkey patching for setup.py is done now through globals() parameter
  of exec. [IMP] Filling README.md. [Luis Alejandro Martínez Faneyth]

- [REF] Adding methods to access each setup.py and ask him directly
  which packages provides. [Luis Alejandro Martínez Faneyth]

Fix
~~~

- [FIX] Fixing travis syntax. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing case when a json gets corrupted. [Luis Alejandro Martínez
  Faneyth]

- [FIX] Minor message change. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing commit errors. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing various errors. [Luis Alejandro Martínez Faneyth]

- [FIX] Updating auth token. [REF] Refactoring to make less calls to
  read/write on disk per package. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing Travis push to github. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing Travis syntax. [Luis Alejandro Martínez Faneyth]

- [FIX] Bypassing open function. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing unicode mess. [Luis Alejandro Martínez Faneyth]

- [FIX] StringIO input can't be str. [IMP] Catching download errors.
  [Luis Alejandro Martínez Faneyth]

- [FIX] Only fail open when en reading mode and file doesn't exist.
  [Luis Alejandro Martínez Faneyth]

- [FIX] Improving method to remove comments and docstrings. [Luis
  Alejandro Martínez Faneyth]

- [FIX] pypicontents/utils.py: Removing multiline comments from original
  setup.py too. [Luis Alejandro Martínez Faneyth]

- [FIX] Escaping URLs because someone uploaded a package file with
  spcaes in its name. One see things in this life ... [Luis Alejandro
  Martínez Faneyth]

- [FIX] Catch SSL error on XMLRPC API. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing typos, dah. [Luis Alejandro Martínez Faneyth]

- [FIX] Catching post cleaning exceptions. [Luis Alejandro Martínez
  Faneyth]

- [FIX] Moving monkeypatchs into the loop because these fuckers can
  override my monkepatching. Seriously, dudes. [Luis Alejandro Martínez
  Faneyth]

- [FIX] Catching exit be cause i don't want you to. [Luis Alejandro
  Martínez Faneyth]

- [FIX] Adding condition for when packages is an empty list. [Luis
  Alejandro Martínez Faneyth]

- [FIX] Fixing parameter order. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing pygrep function, which wasn't what i wanted. [Luis
  Alejandro Martínez Faneyth]

- [FIX] Fixing typo. [Luis Alejandro Martínez Faneyth]

- [FIX] Adding method to find correct setup.py if not present where it
  should be. [Luis Alejandro Martínez Faneyth]

- [FIX] Add try-except to handle erroneous setup.py (shame). [Luis
  Alejandro Martínez Faneyth]

- [FIX] Adding package path to sys.path in case someone imports itself
  on setup.py. [Luis Alejandro Martínez Faneyth]

- [FIX] Changing cache dir so that we can tak advantage from Travis's
  cache. [Luis Alejandro Martínez Faneyth]

- [FIX] Fixing typo. [Luis Alejandro Martínez Faneyth]

- [FIX] Fin tunning travis file for pushing to GH. [Luis Alejandro
  Martínez Faneyth]

Other
~~~~~

- Importing PyPIrazzi source code. [Luis Alejandro Martínez Faneyth]

- Simplifying dockerfiles. [Luis Alejandro Martínez Faneyth]

- Including dockerfiles. Moving scripts to separate package: pypirazzi.
  [Luis Alejandro Martínez Faneyth]

- Fixing logger and scripts. [Luis Alejandro Martínez Faneyth]

- General improvements. [Luis Alejandro Martínez Faneyth]

- Fixing minor bug. [Luis Alejandro Martínez Faneyth]

- Fixing some errored packages. [Luis Alejandro Martínez Faneyth]

- Improving Readme. [Luis Alejandro Martínez Faneyth]

- [IMP] Implementing theard stop. [Luis Alejandro Martínez Faneyth]

- [IMP] Disabling location of setup.py. [Luis Alejandro Martínez
  Faneyth]

- [IMP] Configuring a logger for output messages. [IMP] Adding Python 3+
  compatibility. [IMP] Dividing package parsing by letter to take
  advantage of travis parallel jobs. [FIX] Leaving package archive for
  travis to cache. [FIX] Correcting duplicate logger issue. [IMP]
  Handling KeyboardInterruption and timeouts. [IMP] Fixing logging.
  [Luis Alejandro Martínez Faneyth]

- [IMP] Changing back multiprocessing to threading. [Luis Alejandro
  Martínez Faneyth]

- [IMP] Configuring thread to stop after 20s if the setup hasn't
  finished. [Luis Alejandro Martínez Faneyth]

- [IMP] Adding more modules to fake. [Luis Alejandro Martínez Faneyth]

- [IMP] Adding more modules to fake. [Luis Alejandro Martínez Faneyth]

- [IMP] Improving the creation of missing file. [Luis Alejandro Martínez
  Faneyth]

- [IMP] Improve the handling of IOError. [Luis Alejandro Martínez
  Faneyth]

- [IMP] Improving module logic. [Luis Alejandro Martínez Faneyth]

- [IMP] Improving function to remove comments and docstrings. [Luis
  Alejandro Martínez Faneyth]

- [IMP] Write to disk in every package and not at the end. [Luis
  Alejandro Martínez Faneyth]

- [IMP] README.md: Improving use cases and description. [IMP]
  pypicontents/utils.py: Implementing threading. [IMP]
  pypicontents/patches.py: improving excecution of setup.py. [Luis
  Alejandro Martínez Faneyth]

- Updating $GHTOKEN on .travis.yml [FIX] If we find an unsupported
  archive type, continue and do not break. [Luis Alejandro Martínez
  Faneyth]

- [IMP] Improving try-except on setup.py execution. [ADD] Moving
  functions to separate script. [Luis Alejandro Martínez Faneyth]

- Initial commit. [Luis Alejandro Martínez Faneyth]


