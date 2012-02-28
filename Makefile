# Makefile

SHELL = sh -e

# Project data
AUTHOR = Luis Alejandro Martínez Faneyth
EMAIL = luis@huntingbears.com.ve
MAILIST = aguilas-list@googlegroups.com
PACKAGE = Aguilas
CHARSET = UTF-8
VERSION = $(shell cat VERSION | grep "VERSION" | sed 's/VERSION = //g;s/+.*//g')
YEAR = $(shell date +%Y)

# Translation data
LANGUAGETEAM = Aguilas Translation Team <aguilas-list@googlegroups.com>
POTLIST = locale/pot/aguilas/POTFILES.in
POTFILE = locale/pot/aguilas/messages.pot
POTITLE = Aguilas Translation Template
POTEAM = Aguilas Translation Team
PODATE = $(shell date +%F\ %R%z)

# Common files lists
IMAGES = $(shell ls themes/canaima/images/ | grep "\.svg" | sed 's/\.svg//g')
THEMES = $(shell ls themes/)
LOCALES = $(shell find locale -mindepth 1 -maxdepth 1 -type d | sed 's|locale/pot||g;s|locale/||g')
PHPS = $(wildcard *.php)
ALLPHPS = $(shell find . -type f -iname "*.php")

# Build depends
# User build tasks
# gen-img: generates png images from svg and favicons. Uses CONVERT, LIBSVG and ICOTOOL.
# gen-mo: generates mo files from po files. Uses MSGFMT.
# gen-doc: builds all documentation.
# 	- gen-man: generates a man page. Uses RST2MAN.
# 	- gen-wiki: generates github and google code wikis from rest sources. Uses PYTHON.
# 	- gen-html: generates the HTML manual from rest sources. Uses SPHINX
# gen-conf: generates configuration file from user input. Uses BASH.
PYTHON = $(shell which python)
BASH = $(shell which bash)
RST2MAN = $(shell which rst2man)
SPHINX = $(shell which sphinx-build)
MSGFMT = $(shell which msgfmt)
CONVERT = $(shell which convert)
ICOTOOL = $(shell which icotool)
LIBSVG = $(shell find /usr/lib/ -maxdepth 1 -type d -iname "imagemagick-*")/modules-Q16/coders/svg.so

# Install depends
# User install tasks
# install: install aguilas
#	- copy: copies files over their destination. They need PHP, PHPLDAP and PHPMYSQL.
#	- config: creates MYSQL tables and LDAP entries.
PHP = $(shell which php5)
PHPLDAP = $(shell find /usr/lib/ -name "mysql.so" | grep "php5")
PHPMYSQL = $(shell find /usr/lib/ -name "ldap.so" | grep "php5")
PHPMCRYPT = $(shell find /usr/lib/ -name "mcrypt.so" | grep "php5")
PHPMHASH = $(shell find /usr/lib/ -name "mhash.so" | grep "php5")

# Maintainer tasks depends
# generatepot: generates POT template from php sources. Uses XGETTEXT.
# updatepos: updates PO files from POT files. Uses MSGMERGE.
# snapshot: makes a new development snapshot. Uses BASH and GIT.
# release: makes a new release. Uses BASH, GIT, PYTHON, MD5SUM, TAR and GBP.
PYTHON = $(shell which python)
BASH = $(shell which bash)
GIT = $(shell which git)
MSGMERGE = $(shell which msgmerge)
XGETTEXT = $(shell which xgettext)
DEVSCRIPTS = $(shell which debuild)
DPKGDEV = $(shell which dpkg-buildpackage)
DEBHELPER = $(shell which dh)
GBP = $(shell which git-buildpackage)
LINTIAN = $(shell which lintian)
GNUPG = $(shell which gpg)
MD5SUM = $(shell which md5sum)
TAR = $(shell which tar)
TRANSIFEX = $(shell which tx)

# BUILD TASKS ------------------------------------------------------------------------------

all: gen-img gen-mo gen-conf

build: gen-img gen-mo gen-doc

build-all: gen-img gen-po gen-mo gen-doc gen-conf

gen-doc: gen-wiki gen-html gen-man

gen-predoc: clean-predoc

	@echo "Preprocessing documentation ..."
	@$(BASH) tools/predoc.sh build

gen-wiki: check-buildep gen-predoc clean-wiki

	@echo "Generating documentation from source [RST > WIKI]"
	@cp documentation/githubwiki.index documentation/rest/Home.md
	@cp documentation/rest/*.md documentation/rest/*.rest documentation/githubwiki/
	@rm -rf documentation/rest/Home.md
	@cp documentation/googlewiki.index documentation/rest/index.rest
	@echo "" >> documentation/rest/index.rest
	@cat documentation/rest/contents.rest >> documentation/rest/index.rest
	@mv documentation/rest/contents.rest documentation/rest/contents.tmp
	@$(PYTHON) -B tools/googlecode-wiki.py
	@mv documentation/rest/contents.tmp documentation/rest/contents.rest
	@rm -rf documentation/rest/index.rest

gen-html: check-buildep gen-predoc clean-html

	@echo "Generating documentation from source [RST > HTML]"
	@cp documentation/sphinx.index documentation/rest/index.rest
	@$(SPHINX) -E -Q -b html -d documentation/html/doctrees documentation/rest documentation/html
	@rm -rf documentation/rest/index.rest documentation/html/doctrees documentation/html/objects.inv

gen-man: check-buildep gen-predoc clean-man

	@echo "Generating documentation from source [RST > MAN]"
	@$(RST2MAN) --language="en" --title="AGUILAS" documentation/man/aguilas.rest documentation/man/aguilas.1

gen-img: check-buildep clean-img

	@printf "Generating images from source [SVG > PNG,ICO] ["
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			$(CONVERT) -background None themes/$${THEME}/images/$${IMAGE}.svg \
				themes/$${THEME}/images/$${IMAGE}.png; \
			printf "."; \
		done; \
		$(ICOTOOL) -c -o themes/$${THEME}/images/favicon.ico \
			themes/$${THEME}/images/favicon.png; \
	done
	@printf "]\n"

gen-mo: check-buildep clean-mo

	@printf "Generating translation messages from source [PO > MO] ["
	@for LOCALE in $(LOCALES); do \
		$(MSGFMT) locale/$${LOCALE}/LC_MESSAGES/messages.po \
			-o locale/$${LOCALE}/LC_MESSAGES/messages.mo; \
		printf "."; \
	done
	@printf "]\n"

gen-conf: check-buildep clean-conf

	@echo "Filling up configuration"
	@$(BASH) tools/gen-conf.sh
	@echo "Configuration file generated!"

# INSTALL TASKS ------------------------------------------------------------------------------

install: copy config

config: check-instdep

	@mkdir -p /var/www/
	@mkdir -p /var/log/aguilas/
	@touch /var/log/aguilas/{Ajax.log,ChangePasswordDo.log,DeleteUserDo.log,NewUserMail.log,NewUserDo.log,ResendMailDo.log,ResetPasswordDo.log,ResetPasswordMail.log}
	@chown www-data:www-data /var/log/aguilas/*.log
	@chmod 640 /var/log/aguilas/*.log
	@ln -s /usr/share/aguilas/ /var/www/aguilas
	@$(PHP) -f setup/install.php
	@echo "AGUILAS configured and running!"

copy:

	@mkdir -p $(DESTDIR)/usr/share/aguilas/setup/

	@# Installing application
	@cp -r locale libraries themes $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 $(PHPS) $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 setup/config.* $(DESTDIR)/usr/share/aguilas/setup/

	@# Removing unnecesary svg's
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			rm -rf $(DESTDIR)/usr/share/aguilas/themes/$${THEME}/images/$${IMAGE}.svg; \
		done; \
		rm -rf themes/$${THEME}/images/favicon.png; \
	done
	@echo "Files copied"

uninstall: check-instdep

	@$(PHP) -f setup/uninstall.php
	@rm -rf /usr/share/aguilas/
	@rm -rf /var/log/aguilas/
	@rm -rf /var/www/aguilas
	@echo "Uninstalled"

reinstall: uninstall install


# MAINTAINER TASKS ---------------------------------------------------------------------------------

prepare: check-maintdep

	@git submodule init
	@git submodule update
	@cd documentation/githubwiki/ && git checkout development && git pull origin development
	@cd documentation/googlewiki/ && git checkout development && git pull origin development

pull-po:

	@tx pull -a

push-po:

	@tx push --source --translations

gen-po: check-maintdep gen-pot

	@echo "Updating PO files ["
	@for LOCALE in $(LOCALES); do \
		$(MSGMERGE) --no-wrap -s -U locale/$${LOCALE}/LC_MESSAGES/messages.po $(POTFILE); \
		sed -i -e ':a;N;$$!ba;s|#, fuzzy\n||g' locale/$${LOCALE}/LC_MESSAGES/messages.po; \
		rm -rf locale/$${LOCALE}/LC_MESSAGES/messages.po~; \
	done
	@echo "]"

gen-pot: check-maintdep

	@echo "Updating POT template ..."
	@rm $(POTLIST)
	@for FILE in $(ALLPHPS); do \
		echo "../../.$${FILE}" >> $(POTLIST); \
	done
	@cd locale/pot/aguilas/ && $(XGETTEXT) --msgid-bugs-address="$(MAILIST)" \
		--package-version="$(VERSION)" --package-name="$(PACKAGE)" \
		--copyright-holder="$(AUTHOR)" --no-wrap --from-code=utf-8 \
		--language=php -k_ -s -j -o messages.pot -f POTFILES.in
	@sed -i -e 's/# SOME DESCRIPTIVE TITLE./# $(POTITLE)./' \
		-e 's/# Copyright (C) YEAR Luis Alejandro Martínez Faneyth/# Copyright (C) $(YEAR) $(AUTHOR)/' \
		-e 's/same license as the PACKAGE package./same license as the $(PACKAGE) package./' \
		-e 's/# FIRST AUTHOR <EMAIL@ADDRESS>, YEAR./#\n# Translators:\n# $(AUTHOR) <$(EMAIL)>, $(YEAR)/' \
		-e 's/"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"/"PO-Revision-Date: $(PODATE)\\n"/' \
		-e 's/"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"/"Last-Translator: $(AUTHOR) <$(EMAIL)>\\n"/' \
		-e 's/"Language-Team: LANGUAGE <LL@li.org>\\n"/"Language-Team: $(POTEAM) <$(MAILIST)>\\n"/' \
		-e 's/"Language: \\n"/"Language: English\\n"/g' $(POTFILE)
	@sed -i -e ':a;N;$$!ba;s|#, fuzzy\n||g' $(POTFILE)

snapshot: check-maintdep prepare gen-html gen-wiki gen-po clean

	@$(MAKE) clean
	@$(BASH) tools/snapshot.sh

release: check-maintdep

	@$(BASH) tools/release.sh

deb-test-snapshot: check-maintdep

	@$(BASH) tools/buildpackage.sh test-snapshot

deb-test-release: check-maintdep

	@$(BASH) tools/buildpackage.sh test-release

deb-final-release: check-maintdep

	@$(BASH) tools/buildpackage.sh final-release

# CLEAN TASKS ------------------------------------------------------------------------------

clean: clean-img clean-mo clean-man clean-conf clean-predoc

clean-all: clean-img clean-mo clean-html clean-wiki clean-man clean-conf clean-predoc

clean-predoc:

	@echo "Cleaning preprocessed documentation files ..."
	@$(BASH) tools/predoc.sh clean
	@rm -rf documentation/rest/index.rest

clean-img:

	@printf "Cleaning generated images [PNG,ICO] ["
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			rm -rf themes/$${THEME}/images/$${IMAGE}.png; \
			printf "."; \
		done; \
		rm -rf themes/$${THEME}/images/favicon.ico; \
	done
	@printf "]\n"

clean-mo:

	@printf "Cleaning generated localization ["
	@for LOCALE in $(LOCALES); do \
		rm -rf locale/$${LOCALE}/LC_MESSAGES/messages.mo; \
		printf "."; \
	done
	@printf "]\n"

clean-html:

	@echo "Cleaning generated html ..."
	@rm -rf documentation/html/*
	@rm -rf documentation/html/.buildinfo

clean-wiki:

	@echo "Cleaning generated wiki pages ..."
	@rm -rf documentation/googlewiki/*
	@rm -rf documentation/githubwiki/*

clean-man:

	@echo "Cleaning generated man pages ..."
	@rm -rf documentation/man/aguilas.1

clean-conf:

	@echo "Cleaning generated configuration ..."
	@rm -rf setup/config.php

# CHECK DEPENDENCIES ---------------------------------------------------------------------------------------------

check-instdep:

	@printf "Checking if we have PHP ... "
	@if [ -z $(PHP) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-cli\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP LDAP support ... "
	@if [ -z $(PHPLDAP) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-ldap\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP MYSQL support ... "
	@if [ -z $(PHPMYSQL) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-mysql\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP MCRYPT support ... "
	@if [ -z $(PHPMCRYPT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-mcrypt\" package."; \
		exit 1; \
	fi
	@echo


	@printf "Checking if we have PHP MHASH support ... "
	@if [ -z $(PHPMHASH) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-mhash\" package."; \
		exit 1; \
	fi
	@echo

check-maintdep:

	@printf "Checking if we have python ... "
	@if [ -z $(PYTHON) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have bash... "
	@if [ -z $(BASH) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"bash\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have git... "
	@if [ -z $(GIT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"git-core\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have xgettext ... "
	@if [ -z $(XGETTEXT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"gettext\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have msgmerge ... "
	@if [ -z $(MSGMERGE) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"gettext\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have debhelper ... "
	@if [ -z $(DEBHELPER) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"debhelper\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have devscripts ... "
	@if [ -z $(DEVSCRIPTS) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"devscripts\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have dpkg-dev ... "
	@if [ -z $(DPKGDEV) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"dpkg-dev\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have git-buildpackage ... "
	@if [ -z $(GBP) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"git-buildpackage\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have lintian ... "
	@if [ -z $(LINTIAN) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"lintian\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have gnupg ... "
	@if [ -z $(GNUPG) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"gnupg\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have md5sum ... "
	@if [ -z $(MD5SUM) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"coreutils\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have tar ... "
	@if [ -z $(TAR) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"tar\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have tx ... "
	@if [ -z $(TRANSIFEX) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"transifex-client\" package."; \
		exit 1; \
	fi
	@echo

check-buildep:

	@printf "Checking if we have python ... "
	@if [ -z $(PYTHON) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have bash... "
	@if [ -z $(BASH) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"bash\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have sphinx-build ... "
	@if [ -z $(SPHINX) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python-sphinx\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have convert ... "
	@if [ -z $(CONVERT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"imagemagick\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have rst2man ... "
	@if [ -z $(RST2MAN) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python-docutils\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have msgfmt ... "
	@if [ -z $(MSGFMT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"gettext\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have icotool ... "
	@if [ -z $(ICOTOOL) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"icoutils\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have imagemagick svg support ... "
	@if [ -z $(LIBSVG) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"libmagickcore-extra\" package."; \
		exit 1; \
	fi
	@echo
