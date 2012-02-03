Summary
-------

*Aguilas* is a centralized registration system that allows users to create and manage their accounts on an LDAP authentication platform and (in some cases) using MYSQL databases to store temporary records. It's mostly written in PHP, and depends heavily on the use of LDAP and MYSQL servers. Aguilas has the following features:

* Creates user accounts based on determined LDAP attributes.
* Resends confirmation email in case it gets lost on spam folders.
* Changes user password, if the user knows it.
* Resets user password, using email as confirmation.
* Reminds username.
* Deletes user accounts.
* Edits user profile (ajax enabled).
* Lists all registered users.
* Searches for a term in the user database.

Installing
----------

This is a *extremely quick* installation howto, read more at the `wiki <http://code.google.com/p/aguilas/wiki>`_ for details.

First, you have to install the software dependencies::

	aptitude install apache2 php5 php5-gd php5-ldap php5-mcrypt php5-mysql php5-suhosin php5-cli make bash gettext python-sphinx icoutils python-docutils libmagickcore-extra imagemagick apache2 mysql-server slapd postfix

Then::

	make

Obtain superuser powers and::

	make install

Documentation
-------------

Take a look at the `wiki <http://code.google.com/p/aguilas/wiki>`_.

Contributing
------------

+ Report bugs and request features in our `ticket tracker <https://github.com/HuntingBears/aguilas/issues>`_.
+ Submit patches or pull requests for new and/or fixed behavior.
+ Join the `Aguilas mailing list <http://groups.google.com/group/aguilas-list>`_ and share your ideas.
+ Improve Aguilas documentation by reading, writing new articles or correcting existing ones.
+ Translate Aguilas into your local language, by joining our translation team.

You can find more details at the `wiki <http://code.google.com/p/aguilas/wiki>`_.

Translating
-----------

More information on the `wiki <http://code.google.com/p/aguilas/wiki>`_.









--

* AGUILAS is the achronym for "Aplicación para la Gestión de Usuarios con Interfaz para LDAP Amigable y Segura" in spanish. The `original author <http://www.huntingbears.com.ve/acerca>`_ is from Caracas, Venezuela.
