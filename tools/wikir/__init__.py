
"""Publish RST documents in Wiki format.

.. contents::

Summary
-------

Wikir (pronounced "wicker") converts `reStructuredText`_ documents to various Wiki formats.  Currently, `Google Code Wiki Syntax`_ is the target, but compatibility with `Moin Moin Wiki Syntax`_ and `Trac Wiki Syntax`_ is maintained when possible.

.. _Google Code Wiki Syntax: http://code.google.com/p/support/wiki/WikiSyntax
.. _Moin Moin Wiki Syntax: http://musicbrainz.org/doc/MoinMoinWikiSyntax
.. _Trac Wiki Syntax: http://trac.edgewall.org/wiki/WikiFormatting
.. _reStructuredText: http://docutils.sourceforge.net/docs/user/rst/quickref.html

Installation
------------

::

  easy_install wikir

Or check out a development version from the `subversion repository`_.

.. _subversion repository: http://wikir.googlecode.com/svn/trunk/#egg=wikir-dev

How Much reStructuredText Is Supported?
---------------------------------------

Not a whole lot!  Since `RST syntax`_ is huge and `Google Code Wiki Syntax`_ is small, RST may never be fully supported.  However, if you come across an unsupported RST directive please `submit an issue`_ and include the snippet of RST and how you think it should be displayed in wiki syntax.

.. _submit an issue: http://code.google.com/p/wikir/issues/list
.. _RST syntax: http://docutils.sourceforge.net/docs/user/rst/quickref.html

If possible, also submit a failing test for the new syntax you'd like supported.  You can see all currently tested and implemented RST syntax elements by running the following command (requires `nose`_)::

    nosetests -v ./wikir/tests/test_syntax.py -a '!deferred'

Remove the ``-a '!deferred'`` to see those that still need implementing.  Patches gladly accepted ;)

.. _nose: http://somethingaboutorange.com/mrl/projects/nose/

Using Wikir In Your Project
---------------------------

The publish_wiki Command
++++++++++++++++++++++++

After installing wikir, all `setuptools`_-enabled projects on the same machine will grow a new command, ``publish_wiki``.  A setuptools project is one with a setup.py file like so::

  from setuptools import setup
  setup(
      name='MyModule',
      version='0.999',
      # ...etc...
  )
  
.. _setuptools: http://pypi.python.org/pypi/setuptools

Command usage::

    $ python setup.py publish_wiki --help
    | Common commands: (see '--help-commands' for more)
    | 
    |   setup.py build      will build the package underneath 'build/'
    |   setup.py install    will install the package
    | 
    | Global options:
    |   --verbose (-v)  run verbosely (default)
    |   --quiet (-q)    run quietly (turns verbosity off)
    |   --dry-run (-n)  don't actually do anything
    |   --help (-h)     show detailed help message
    | 
    | Options for 'publish_wiki' command:
    |   --source (-s)  path to RST source.  if a python module, the top-most
    |                  docstring will be used as the source
    | 
    | usage: setup.py [global_opts] cmd1 [cmd1_opts] [cmd2 [cmd2_opts] ...]
    |    or: setup.py --help [cmd1 cmd2 ...]
    |    or: setup.py --help-commands
    |    or: setup.py cmd --help
    | 


Publishing RST To Wiki
++++++++++++++++++++++

Here is an example of publishing a module's docstring written in RST format to Wiki format::
    
    $ cd examples/basic
    $ cat ./akimbo/__init__.py
    | '''
    | Welcome To Akimbo
    | =================
    | 
    | A Python module for akimbo'ing.
    | 
    | This could live on `Google Code`_ if it wanted to.
    | 
    | .. _Google Code: http://code.google.com/hosting/
    | 
    | '''
    $ python setup.py publish_wiki --source=./akimbo/__init__.py
    | = Welcome To Akimbo =
    | 
    | A Python module for akimbo'ing.
    | 
    | This could live on [http://code.google.com/hosting/ Google Code] if it wanted to.
    | 

Using Custom RST Directives
+++++++++++++++++++++++++++

Wikir provides an entry_point in case you need to register a `custom RST directive`_.  Here is an example::

    $ cd examples/custom_directives
    $ python setup.py -q develop
    $ cat setup.py
    | from setuptools import setup
    | setup(
    |     name = 'Foozilate',
    |     entry_points = {
    |         'wikir.rst_directives': [
    |             'foozilate = foozilate.directives:foozilate'
    |         ]
    |     },
    |     description = "A mysterious package that aids in foozilation")
    $ cat ./README.txt
    | This is the documentation for foozilate, which requires a custom directive called ``foozilate``.  All it does is wrap some tags around the input.
    | 
    | .. foozilate::
    |     this should be foozilated
    $ python setup.py publish_wiki --source ./README.txt
    | This is the documentation for foozilate, which requires a custom directive called `foozilate`.  All it does is wrap some tags around the input.
    | 
    | --foozilated-- this should be foozilated --foozilated--
    | 
    $ python setup.py -q develop --uninstall

.. note::
    You should avoid putting custom directives in a docstring if you want them to build with a standard docutils install.  For example, your RST docstrings may be parsed automatically by the `The Cheeseshop`_ or another documentation generator, like `pydoctor`_.

.. _pydoctor: http://codespeak.net/~mwh/pydoctor/
.. _The Cheeseshop: http://pypi.python.org/pypi
.. _custom RST directive: http://docutils.sourceforge.net/docs/howto/rst-directives.html

The left side of the entry_point defined the directive name and the right side specifies the path to the directive function.  This is registered with docutils before any RST is published.

The wikir command
+++++++++++++++++

If you don't want to use wikir through setup.py you can use the command line script, `wikir`, which gets installed for you.  

Command usge::

    $ wikir --help
    | Usage: wikir [options] path/to/module.py
    |        wikir [options] path/to/file.txt
    | 
    | Publish RST documents in Wiki format
    | 
    | 1. finds the top-most docstring of module.py, parses as RST, and prints Wiki format to STDOUT
    | 2. parses file.txt (or a file with any other extension) as RST and prints Wiki format to STDOUT
    | 
    | Options:
    |   -h, --help  show this help message and exit

You can publish a module's docstring written in RST format to Wiki format like so::

    $ cd examples/basic
    $ cat ./akimbo/__init__.py
    | '''
    | Welcome To Akimbo
    | =================
    | 
    | A Python module for akimbo'ing.
    | 
    | This could live on `Google Code`_ if it wanted to.
    | 
    | .. _Google Code: http://code.google.com/hosting/
    | 
    | '''
    $ wikir ./akimbo/__init__.py
    | = Welcome To Akimbo =
    | 
    | A Python module for akimbo'ing.
    | 
    | This could live on [http://code.google.com/hosting/ Google Code] if it wanted to.
    | 
    
... and you can publish any document written in RST format to Wiki format like so::

    $ cd examples/basic
    $ cat ./README.txt
    | ========================
    | Documentation for Akimbo
    | ========================
    | 
    | .. contents:: :local:
    | 
    | What is it?
    | ===========
    | 
    | A Python module for akimbo'ing.
    | 
    | Where does it live?
    | ===================
    | 
    | Akimbo could live on `Google Code`_ if it wanted to.
    | 
    | .. _Google Code: http://code.google.com/hosting/
    $ wikir ./README.txt
    | = Documentation for Akimbo =
    | 
    |   * What is it?
    |   * Where does it live?
    | 
    | 
    | = What is it? =
    | 
    | A Python module for akimbo'ing.
    | 
    | = Where does it live? =
    | 
    | Akimbo could live on [http://code.google.com/hosting/ Google Code] if it wanted to.
    | 

Using Wikir Programatically
---------------------------

::

  >>> from wikir import publish_string
  >>> print publish_string('''
  ... My RST Document
  ... ===============
  ... 
  ... For `Google Code`_!
  ... 
  ... .. _Google Code: http://code.google.com/
  ... ''')
  = My RST Document =
  <BLANKLINE>
  For [http://code.google.com/ Google Code]!
  <BLANKLINE>
  <BLANKLINE>
  >>> 

Credits
-------

This work is based on code I found while snooping around the `nose repository`_ (Jason Pellerin), `Ian Bicking's docutils sandbox`_, and `Matthew Gilbert's docutils sandbox`_.  Thanks to `Kent S. Johnson`_ for additional help and feedback.

.. _nose repository: http://python-nose.googlecode.com/svn/trunk/scripts/
.. _Ian Bicking's docutils sandbox: http://docutils.sourceforge.net/sandbox/ianb/wiki/Wiki.py
.. _Matthew Gilbert's docutils sandbox: http://docutils.sourceforge.net/sandbox/mmgilbe/rst.py
.. _Kent S. Johnson: http://personalpages.tds.net/~kent37/

Project Home
------------

If you're not there already, `wikir lives on Google Code`_.

.. _wikir lives on Google Code: http://code.google.com/p/wikir/

"""
__version__ = '0.4.1'

from wikir import *