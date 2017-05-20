
.. _installation methods:

Installation Methods
====================

You can install Stanlee in various ways, depending on your requirements. Here you will find all the supported installation methods. Generally, the best and easier way to install Stanlee on Debian based distributions is to use a Debian package, see more on `installing from a debian package`_. In any case, you will need to read `installing software dependencies`_ before you can actually install Stanlee.

.. _installing software dependencies:

Installing software dependencies
--------------------------------

Stanlee requires the installation of certain software applications so that specific funcionality is available to it's internal processes. As of this documentation, we will be covering the installation of these dependencies on *Debian based distributions* like Ubuntu, Canaima, Linux Mint and Debian (of course). If you use another type of GNU/Linux distribution (or Windows), you might need to investigate which packages or executables you need to install in order to satisfy these dependencies. In any case, you can always take a look at `getting help`_.

Basically, You will need:

+ A web server like Apache, Lighttpd or Nginx.
+ A Mail Transport Agent (MTA) like Exim, Pastfix or Sendmail.
+ PHP 5 or higher (with LDAP, MYSQL, MHASH, MCRYPT and CLI support).
+ Imagemagick image conversion utility with SVG support.
+ Python with Sphinx and Docutils support.
+ Icoutils.
+ Gettext.
+ Bash.
+ Make.

Optionally, you will have to install an LDAP and a MYSQL server depending on where is located the computer you are going to use as a server. If you are going to use a local computer as a server (e.g. your personal computer), then you will have to install and configure both on that computer. If you are going to use a remote computer as a server (e.g. a shared hosting server), then you will have to install and configure LDAP and MYSQL on that remote machine.

If you are impatient, you can install all dependencies at once with the following command::

	aptitude install apache2 php5 php5-gd php5-ldap php5-mcrypt php5-mysql php5-mhash php5-suhosin php5-cli make bash gettext python-sphinx icoutils python-docutils libmagickcore-extra imagemagick apache2 mysql-server slapd postfix

If you want to keep reading, We will now explain how to install and configure each one of these dependencies. If you already have them installed in your working environment, please ignore it and proceed to the next step.

.. _installing apache and php:

Installing Apache and PHP
+++++++++++++++++++++++++

Run the following command as superuser::

	aptitude install apache2 php5 php5-gd php5-ldap php5-mcrypt php5-mhash php5-mysql php5-suhosin php5-cli

.. _installing the mysql server:

Installing the MYSQL server
+++++++++++++++++++++++++++

Run the following command as superuser::

	aptitude install mysql-server

You will be asked for a password for the "root" account for MYSQL. Remember this password as you will be asked for it later.

.. _installing the ldap server:

Installing the LDAP server
+++++++++++++++++++++++++++

Run the following command as superuser::

	aptitude install slapd

You will be asked for a password for the Distinguished Name (DN) of the administrator entry for LDAP; normally, the admin DN is *cn=admin,dc=nodomain*. Remember this password as you will be asked for it later.

.. _installing python modules and other applications:

Installing Python modules and other applications
++++++++++++++++++++++++++++++++++++++++++++++++

Run the following command as superuser::

	aptitude install make bash gettext python-sphinx icoutils python-docutils libmagickcore-extra imagemagick

.. _installing the mta:

Installing the Mail Transport Agent
+++++++++++++++++++++++++++++++++++

If you already have a working Mail Transport Agent, please proceed to the next step.

Normally you can install a *Mail Transport Agent (MTA)* like ``postfix`` or ``exim`` to serve as a mail server on any computer connected to the internet with a public IP address assigned. However, due to the problem of SPAM, many Internet mail servers block mail from unauthenticated dynamic IP addresses, which are common in domestic connections.

One of the existing solutions is to install a mail server to not send mail directly to the destination server, but to use `Google Mail`_ (or other SMTP) to retransmit the messages.

To send email using GMail SMTP server (``smtp.gmail.com``) the connection must be encrypted with TLS. To do this we need three elements:

+ A certificate authenticated by a valid certificate authority for *GMail*.
+ A *GMail* email account.
+ A local MTA.

Installation
~~~~~~~~~~~~

First install *Postfix*, a fairly complete and configurable MTA. Open a root terminal and type the following command::

	aptitude install postfix

Note: Postfix conflicts with Exim, but it is safe to remove exim in favor of postfix.

The process will make us some questions:

+ *Configuration type*: you will select "Web Site".
+ *Mail System Name*: you will enter the domain name of your local mail server. For this case, we can enter the same domain name of your PC. e.g. "OKComputer". 

Done, the installation is completed.

Configuration
~~~~~~~~~~~~~

Then we have to edit the file ``/etc/postfix/main.cf`` and add the following lines at the end of the file::

	relayhost = [smtp.gmail.com]:587
	smtp_sasl_auth_enable = yes
	smtp_sasl_password_maps = hash:/etc/postfix/sasl/passwd
	smtp_sasl_security_options = noanonymous
	smtp_use_tls = yes
	smtp_tls_CAfile = /etc/postfix/cacert.pem 

There we are telling ``postfix`` to use ``relayhost`` to connect to the Gmail server, which uses ``smtp_sasl_password_maps`` to extract the SASL data connection and use ``smtp_tls_CAfile`` as a certificate for the secure connection.

We must create the file ``/etc/postfix/sasl/passwd`` with the following contents::

	[smtp.gmail.com]:587    [ACCOUNT]@gmail.com:[PASSWORD]

Where ``[ACCOUNT]`` is the gmail account name and ``[PASSWORD]``, the password of ``[ACCOUNT]``.

For example, we could use this command::

	echo "[smtp.gmail.com]:587    luis@gmail.com:123456" > /etc/postfix/sasl/passwd 

Then we must restrict it's access::

	chmod 600 /etc/postfix/sasl/passwd 

Next, we must transform the file into a postfix indexed hash file with the command::

	postmap /etc/postfix/sasl/passwd

That will create the file ``/etc/postfix/sasl/passwd.db``

Certification
~~~~~~~~~~~~~

We have to install the SSL certificates of certification authorities to perform this step. We can install them like this::

	aptitude install ca-certificates

To add the *Equifax certificate authority* (which certifies emails from Gmail) to authorized certificates that use postfix, run the following command in a root console::

	cat /etc/ssl/certs/Equifax_Secure_CA.pem > /etc/postfix/cacert.pem 

Testing
~~~~~~~

Finally, restart postfix to apply the changes, as follows::

	/etc/init.d/postfix restart

You can check it's proper functioning by opening two consoles. In one execute the following command to monitor it's behavior (as root)::

	tail -f /var/log/mail.log 

And in the other send a mail::

	echo "This is a test mail" | mail test@gmail.com 

If you did things right, on the other console you should see something like this::

	Dec 18 18:33:40 OKComputer postfix/pickup[10945]: 75D4A243BD: uid=0 from=
	Dec 18 18:33:40 OKComputer postfix/cleanup[10951]: 75D4A243BD: message-id=
	Dec 18 18:33:40 OKComputer postfix/qmgr[10946]: 75D4A243BD: from=, size=403, nrcpt=1 (queue active)
	Dec 18 18:33:44 OKComputer postfix/smtp[10953]: 75D4A243BD: to=<test@gmail.com>, relay=smtp.gmail.com[74.125.93.109]:587, delay=3.7, delays=0.15/0.14/1.8/1.6, dsn=2.0.0, status=sent (250 2.0.0 OK 1324249500 eb5sm36008464qab.10)
	Dec 18 18:33:44 OKComputer postfix/qmgr[10946]: 75D4A243BD: removed

.. _installing the stable version:

Installing the stable version
-----------------------------

#. Download the source tarball from `Stanlee download page`_. Select the version of your preference, usually the last one will be more complete.

#. Decompress the source with your favorite program::

	e.g.: tar -xvf stanlee-1.0.0.tar.gz

#. Access the uncompressed source::

	e.g.: cd stanlee-1.0.0/

#. Build the sources::

	make


  You will be promted with the following questions to configure AGUILAS:
    + Name of the Application, e.g.: Stanlee for Debian User Management
    + The person or group responsible for managing the application, e.g.: Debian Admins
    + The e-mail address that will appear as sender in all operation e-mails to registered users, e.g.: stanlee@debian.org
    + The e-mail address you wish to use for sending error reports, e.g.: admins@debian.org
    + The language that you wish to see in your application (must be available on *locale* folder), e.g.: en_US
    + The theme applied to the application (must be available in *themes* folder), e.g.: debian
    + The public address of the aplication, e.g.: stanlee.debian.org
    + IP or Domain of the server where the MYSQL database is located, e.g.: localhost
    + MYSQL database name (will be created if it doesn't exist), e.g.: stanlee
    + User with permissions to read and create tables on the database, e.g.: root
    + Password for the MYSQL user, e.g.: 123456
    + IP or Domain of the server where the LDAP service is located, e.g.: localhost
    + DN with read-write priviledges on the LDAP server, e.g.: cn=admin,dc=nodomain
    + Password for the writer DN, e.g.: 123456
    + Base DN to perform searches and include new users, e.g.: dc=nodomain

  If you need to modify these parameters, you can always edit ``/usr/share/stanlee/setup/config.php`` after installation.

#. Obtain superuser priviledges, and install stanlee::

	sudo make install

.. _installing from a debian package:

Installing the stable version using a debian package
----------------------------------------------------

Stanlee is distributed in various Debian derivatives. Grab the latest debian package from the `Stanlee download page`_ and install it with the following command::

	dpkg -i [PATH/TO/FILE]

.. _downloading the development version:

Download the development version
--------------------------------

If you want to download the development version of Stanlee, which has the lastest changes and new features, you can follow the procedure described below. You should install this version if you are planning to contribute in Stanlee development or try some hard-on new features. You should also know that a development version is likely to be unstable or even broken, as it is being developed actively.

That being said:

#. Start cloning the development branch from Stanlee (you will need to install the ``git-core`` package first)::

	git clone --branch development https://github.com/HuntingBears/stanlee.git

#. Access the folder that has just been created::

	cd stanlee

#. Prepare and update the sources::

	make prepare

That's it. You have the latest code from Stanlee. If you want to install it, you can follow the same procedure described at `installing the stable version`_.