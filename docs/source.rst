.. _development cycle:

Describing Stanlee's develpment cycle
=====================================

Stanlee's stable release version numbering works as follows:

+ Stable versions are numbered in the form ``X.Y.Z``.
	* **X** is the major version number, which is only incremented for major changes to Stanlee.
	* **Y** is the minor version number, which is incremented for large yet backwards compatible changes.
	* **Z** is the micro version number, which is incremented for bug and security fixes.
	* In some cases, we'll make alpha, beta, or release candidate releases. These are of the form ``X.Y.Z+{alpha,beta,rc}N``, which means the Nth alpha, beta or release candidate of version X.Y.Z.

+ Development versions are numbered in the form ``X.Y.Z+[YEAR][MONTH][DAY][HOUR][MINUTE][SECONDS]``.

.. _understanding stanlee source code:

Understanding Stanlee's Source Code
===================================

When deploying Stanlee into a real production environment, you will almost always want to use an official packaged release (see `installation methods`_) of Stanlee. However, if you'd like to try out in-development code from an upcoming release or contribute to the development of Stanlee, you'll need to obtain a copy from Stanlee's source code repository. This document covers the way the code repository is laid out and how to work with and find things in it.

.. _stanlee repositories:

Stanlee repositories
--------------------

The Stanlee development model uses *git* (see `working with git`_) to track changes to the code over time and manage collaboration. You'll need a copy of the git client program on your computer, and you'll want to familiarize yourself with the basics of `working with git`_.

The Stanlee git repository is located online at various places, all syncronized with the last code from the authors. We host Stanlee on `Google Code`_, GitHub_, and Gitorious_. In terms of source code, all of them have the same information, so, you can choose the one you like the most. We use GitHub for tracking bugs and other project management stuff. If you want to contribute to Stanlee, please read our `contributing to stanlee`_ section.

.. _branches and tags:

Branches and Tags
-----------------

Inside the repository you will find that Stanlee development is divided into four branches: development, release, master and debian.

``development branch``
	This is where the development is actually made, and because of that, the code provided here might be experimental, unstable or broken. If you want to contribute to Stanlee (see `contributing to stanlee`_), *this is where you should start*.

``release branch``
	This is where stable releases are obtained from. After a development cycle is finished, all changes are merged here, plus other *tactical movements* (see `development cycle`_).

``master branch``
	The purpose of the master branch is to serve as a container to build the Stanlee debian package.

Tags in stanlee are done each time a new (development or release) version is made. The difference is that development (*snapshots*) versions are numbered in the form of ``X.Y.Z+[YEAR][MONTH][DAY][HOUR][MINUTE][SECONDS]`` and stable (*releases*) versions are numbered in the form ``X.Y.Z``.

.. _common tasks and procedures:

Common tasks and procedures
---------------------------

Every common task for the Stanlee maintainers, developers, contributors and users are automatized using the Makefile. Here they are listed:

.. _checking dependencies:

Checking dependencies
+++++++++++++++++++++

+ ``make check-buildep``:  Checking build tasks dependencies. It allows the user/developer to check if all software dependencies requiered to build documentation, themes, tranlations and cofigurations are met.
+ ``make check-maintdep``:  Checking maintainer tasks dependencies. It allows the developer to check if all software dependencies requiered to exacute common maintainer (update PO and POT files, make a snapshot, make a release) tasks are met.
+ ``make check-instdep``:  Checking install dependencies. It allows the user to check if all software dependencies for installation are met.

.. _maintainer tasks:

Maintainer tasks
++++++++++++++++

+ ``make gen-po``:  Generates PO files from POT templates. It generates the PO files for every language listed in the ``locale`` folder, using the POT file located at ``locale/pot/stanlee/messages.pot``.
+ ``make gen-pot``:  Generates POT template from PHP sources. It generates the POT template from every traslatable string in the PHP sources passed through.
+ ``make snapshot``:  Generates a development snapshot. 
+ ``make release``:  Generates a stable realease.

.. _build tasks:

Build tasks
+++++++++++

+ ``make gen-img``:  Generates PNG images and ICO icons for each theme, based on the SVG sources.  
+ ``make gen-mo``:  Generates MO files from PO sources.
+ ``make gen-man``:  Generates the MAN page from REST sources.
+ ``make gen-wiki``:  Generates the wiki pages for Google Code Wiki and GitHub Wiki from REST sources
+ ``make gen-html``:  Generates HTML pages from REST sources.
+ ``make gen-doc``:  Executes gen-man, gen-wiki and gen-html.
+ ``make gen-conf``:  Asks the user for the configuration data and builds config.php.

.. _clean tasks:

Clean tasks
+++++++++++

+ ``make clean-img``:  Cleans generated PNG and ICO files.
+ ``make clean-mo``:  Cleans generated MO files.
+ ``make clean-html``:  Cleans generated HTML files.
+ ``make clean-wiki``:  Cleans generated WIKI files.
+ ``make clean-man``:  Cleans generated MAN
+ ``make clean-conf``:  Cleans generated config.php.

.. _installs tasks:

Install tasks
+++++++++++++

+ ``make copy``:  Copies application files to it's destination.
+ ``make config``:  Configures Stanlee using the generated config.php.
+ ``make install``:  Does ``make copy`` and ``make config``.
+ ``make uninstall``:  Deconfigures and removes Stanlee data.
+ ``make reinstall``:  Does ``make uninstall`` and ``make install``.

.. _files and directories:

Files and Directories
----------------------

Stanlee directory structure is distributed in the following form::

	.
	├── [PHP FILES]
	├── [PHP FILES]
	├── [...]
	├── [DOCUMENTATION FOLDER]
	│   ├── [GITHUBWIKI FOLDER]
	│   ├── [GOOGLEWIKI FOLDER]
	│   ├── [HTML FOLDER]
	│   ├── [MAN FOLDER]
	│   └── [REST FOLDER]
	├── [EVENTS FOLDER]
	├── [LIBRARIES FOLDER]
	├── [LOCALE FOLDER]
	│   ├── [LOCALE A]
	│   ├── [LOCALE B]
	│   ├── [...]
	│   └── [POT FOLDER]
	├── [SETUP FOLDER]
	├── [THEMES FOLDER]
	├── [TOOLS FOLDER]
	└── Makefile
