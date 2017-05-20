.. image:: https://gitcdn.xyz/repo/LuisAlejandro/stanlee/master/docs/_static/title.svg

-----

.. image:: https://img.shields.io/pypi/v/stanlee.svg
   :target: https://pypi.python.org/pypi/stanlee
   :alt: PyPI Package

.. image:: https://img.shields.io/travis/LuisAlejandro/stanlee.svg
   :target: https://travis-ci.org/LuisAlejandro/stanlee
   :alt: Travis CI

.. image:: https://coveralls.io/repos/github/LuisAlejandro/stanlee/badge.svg?branch=master
   :target: https://coveralls.io/github/LuisAlejandro/stanlee?branch=master
   :alt: Coveralls

.. image:: https://landscape.io/github/LuisAlejandro/stanlee/master/landscape.svg?style=flat
   :target: https://landscape.io/github/LuisAlejandro/stanlee/master
   :alt: Landscape

.. image:: https://readthedocs.org/projects/stanlee/badge/?version=latest
   :target: https://readthedocs.org/projects/stanlee/?badge=latest
   :alt: Read The Docs

.. image:: https://cla-assistant.io/readme/badge/LuisAlejandro/stanlee
   :target: https://cla-assistant.io/LuisAlejandro/stanlee
   :alt: Contributor License Agreement

.. image:: https://badges.gitter.im/LuisAlejandro/stanlee.svg
   :target: https://gitter.im/LuisAlejandro/stanlee
   :alt: Gitter Chat

*Stanlee* is a centralized registration system that allows users to create and manage their accounts on an LDAP authentication platform and (in some cases) using MYSQL databases to store temporary records. It's mostly written in PHP, and depends heavily on the use of LDAP and MYSQL servers. It has the following features:

* Creates user accounts based on determined LDAP attributes.
* Resends confirmation email in case it gets lost on spam folders.
* Changes user password, if the user knows it.
* Resets user password, using email as confirmation.
* Reminds username.
* Deletes user accounts.
* Edits user profile (ajax enabled).
* Lists all registered users.
* Searches for a term in the user database.

The following topics will certainly help you to know more about Stanlee:

Table of Contents
-----------------

.. toctree::
   :maxdepth: 2

   installation
   usage
   contributing
   patches
   git
   source
   authors
   history
   maintainer
