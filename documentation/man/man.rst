===========
**AGUILAS**
===========
---------------------------------------
A web-based LDAP user management system
---------------------------------------

:Author: @@AUTHOR@@
:Date:   @@DATE@@
:Copyright: GPL-3
:Version: @@VERSION@@
:Manual section: 1
:Manual group: Web
:Tags: ldap, aguilas, user management, ajax, php, mysql
:Support: @@URL@@
:Summary: A web-based LDAP user management system

**DESCRIPTION**
===============

AGUILAS is a centralized registration system that allows users to create and manage their accounts on an LDAP authentication platform and (in some cases) using MYSQL databases to store temporary records. It's mostly written in PHP, and depends heavily on the use of LDAP and MYSQL servers. It has the following features: 
 
 * Creates user accounts based on determined LDAP attributes. 
 * Resends confirmation email in case it gets lost on spam folders. 
 * Changes user password, if the user knows it. 
 * Resets user password, using email as confirmation. 
 * Reminds username. 
 * Deletes user accounts. 
 * Edits user profile (ajax enabled). 
 * Lists all registered users. 
 * Searches for a term in the user database. 
 
Application code is located on /usr/share/aguilas/, logging registry is on /var/log/aguilas/ and documentation on /usr/share/doc/aguilas/.
