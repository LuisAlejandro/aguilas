Installation Methods
====================

You can install aguilas in various ways, depending on your requirements. Here you will find all the installation methods supported by aguilas. In any case, you will need to install some software dependencies before you can actually install aguilas.

Installing Dependencies
-----------------------

You will need:

* A web server like apache2, lighttpd or nginx.
* php5, php5-ldap, php5-mysql, php5-cli, php5-mcrypt
* imagemagick
* libmagickcore-extra (libmagickcore3-extra or libmagickcore4-extra)
* python-docutils
* icoutils
* python-sphinx
* gettext
* bash
* make

You will need also a working Mail Transport Agent (MTA) to allow AGUILAS to send mail on requests. If you are having problems configuring your MTA, you can read `this tutorial <http://www.huntingbears.com.ve/en/utilizando-postfix-para-enviar-correos-a-traves-de-gmail.html>`_ to use Gmail as a MTA.

Optionally, you will have to install an LDAP and a MYSQL server, but it all depends on your setup. If you are going to connect to a remote server, then you don't have to install them. If you are going to use your own computer as a server, then you will have to install and configure the following packages:

* mysql-server
* slapd

Pay attention to the admin password for both applications, as you will be needing them when configuring AGUILAS.

If you are running Debian, Ubuntu or Canaima, you may install the following packages to satisfy all dependencies (including installing LDAP and MYSQL servers locally)::

	aptitude install make bash gettext python-sphinx icoutils python-docutils libmagickcore-extra imagemagick php5 php5-ldap php5-mysql php5-cli php5-mcrypt apache2 mysql-server slapd

Makefile Style
--------------

#. Download the source tarball from `aguilas download page <http://code.google.com/p/aguilas/downloads/list>`_. Select the version of your preference, usually the last one will be more complete.

#. Decompress the source with your favorite program::

	e.g.: tar -xvf aguilas-1.0.0.tar.gz

#. Access the uncompressed source::

	e.g.: cd aguilas-1.0.0/

#. Build the sources::

	make


  You will be promted with the following questions to configure AGUILAS:
    + Name of the Application
    + The person or group responsible for managing the application
    + The e-mail address that will appear as sender in all operation e-mails to registered users
    + The e-mail address you wish to use for sending error reports
    + The language that you wish to see in your application
    + The theme applied to the application
    + The public address of the aplication
    + IP or Domain of the server where the MYSQL database is located
    + MYSQL database name
    + User with permissions to read and create tables on the database
    + Password for the MYSQL user
    + IP or Domain of the server where the LDAP service is located
    + DN with read-write priviledges on the LDAP server
    + Password for the writer DN
    + Base DN to perform searches and include new users

  If you need to modify these parameters, you can always edit ``/usr/share/aguilas/setup/config.php`` after installation.

#. Obtain superuser priviledges, and install aguilas::

	sudo make install









