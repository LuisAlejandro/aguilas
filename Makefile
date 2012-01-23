# Makefile

SHELL = sh -e

AUTHOR = Luis Alejandro Martínez Faneyth
EMAIL = luis@huntingbears.com.ve
MAILIST = aguilas-list@googlegroups.com
PACKAGE = Aguilas
CHARSET = UTF-8
LANGUAGETEAM = Aguilas Translation Team <aguilas-list@googlegroups.com>
VERSION = $(shell cat VERSION | grep "VERSION" | sed 's/VERSION = //g;s/+.*//g')
PODATE = $(shell date +%F\ %R%z)
YEAR = $(shell date +%Y)
POTLIST = locale/pot/aguilas/POTFILES.in
POTFILE = locale/pot/aguilas/messages.pot
POTITLE = Aguilas Translation Template
POTEAM = Aguilas Translation Team

IMAGES = $(shell ls themes/canaima/images/ | grep "\.svg" | sed 's/\.svg//g')
THEMES = $(shell ls themes/)
LOCALES = $(shell find locale -mindepth 2 -maxdepth 2 -type d | sed 's|locale/pot/aguilas||g')
PHPS = $(wildcard *.php)
ALLPHPS = $(shell find . -type f -iname "*.php")
LOGS = $(wildcard events/*.log)

CONVERT = $(shell which convert)
PHP = $(shell which php5)
PYTHON = $(shell which python)
BINBASH = $(shell which bash)
RST2MAN = $(shell which rst2man)
ICOTOOL = $(shell which icotool)
SPHINX = $(shell which sphinx-build)
XGETTEXT = $(shell which xgettext)
MSGMERGE = $(shell which msgmerge)
MSGFMT = $(shell which msgfmt)
LIBSVG = $(shell find /usr/lib/ -maxdepth 1 -type d -iname "imagemagick-*")/modules-Q16/coders/svg.so
PHPLDAP = $(shell find /usr/lib/ -name "mysql.so" | grep "php5")
PHPMYSQL = $(shell find /usr/lib/ -name "ldap.so" | grep "php5")

all: gen-img gen-mo gen-conf

build: gen-img gen-mo gen-doc

build-all: gen-img gen-mo gen-doc gen-conf

check-builddep:

	@printf "Checking if we have bash... "
	@if [ -z $(BINBASH) ]; then \
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

	@printf "Checking if we have python ... "
	@if [ -z $(PYTHON) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python\" package."; \
		exit 1; \
	fi
	@echo

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

gen-img: check-builddep clean-img

	@printf "Generating images from source [SVG > PNG,ICO] ["
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			convert -background None themes/$${THEME}/images/$${IMAGE}.svg themes/$${THEME}/images/$${IMAGE}.png; \
			printf "."; \
		done; \
		icotool -c -o themes/$${THEME}/images/favicon.ico themes/$${THEME}/images/favicon.png; \
	done
	@printf "]\n"

gen-mo: check-builddep clean-mo

	@printf "Generating translation messages from source [PO > MO] ["
	@for LOCALE in $(LOCALES); do \
		msgfmt $${LOCALE}/messages.po -o $${LOCALE}/messages.mo; \
		printf "."; \
	done
	@printf "]\n"

gen-po: check-builddep gen-pot

	@echo "Updating PO files ["
	@for LOCALE in $(LOCALES); do \
		$(MSGMERGE) --no-wrap -s -U $${LOCALE}/messages.po $(POTFILE); \
		rm -rf $${LOCALE}/messages.po~; \
	done
	@echo "]"

gen-pot: check-builddep

	@echo "Updating POT template ..."
	@rm $(POTLIST)
	@for FILE in $(ALLPHPS); do \
		echo "../../.$${FILE}" >> $(POTLIST); \
	done
	@cd locale/pot/aguilas/ && $(XGETTEXT) --msgid-bugs-address="$(MAILIST)" \
		--package-version="$(VERSION)" --package-name="$(PACKAGE)" \
		--copyright-holder="$(AUTHOR)" --no-wrap --from-code=utf-8 \
		--language=php -k_ -s -o messages.pot -f POTFILES.in
	@sed -i -e 's/# SOME DESCRIPTIVE TITLE./# $(POTITLE)./' \
		-e 's/# Copyright (C) YEAR Luis Alejandro Martínez Faneyth/# Copyright (C) $(YEAR) $(AUTHOR)/' \
		-e 's/same license as the PACKAGE package./same license as the $(PACKAGE) package./' \
		-e 's/# FIRST AUTHOR <EMAIL@ADDRESS>, YEAR./#\n# Translators:\n# $(AUTHOR) <$(EMAIL)>, $(YEAR)/' \
		-e 's/"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"/"PO-Revision-Date: $(PODATE)\\n"/' \
		-e 's/"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"/"Last-Translator: $(AUTHOR) <$(EMAIL)>\\n"/' \
		-e 's/"Language-Team: LANGUAGE <LL@li.org>\\n"/"Language-Team: $(POTEAM) <$(MAILIST)>\\n"/' \
		-e 's/"Language: \\n"/"Language: English\\n"/g' $(POTFILE)

gen-doc: gen-wiki gen-html gen-man

predoc: clean-predoc

	@echo "Preprocessing documentation ..."
	@bash tools/predoc.sh

gen-wiki: check-builddep predoc clean-wiki

	@echo "Generating documentation from source [RST > WIKI]"
	@cp documentation/githubwiki.index documentation/rest/index.rest
	@cp documentation/rest/*.rest documentation/githubwiki/
	@cp documentation/googlewiki.index documentation/rest/index.rest
	@python -B tools/googlecode-wiki.py
	@rm -rf documentation/rest/index.rest

gen-html: check-builddep predoc clean-html

	@echo "Generating documentation from source [RST > HTML]"
	@cp documentation/sphinx.index documentation/rest/index.rest
	@sphinx-build -a -E -Q -b html -d documentation/html/doctrees documentation/rest documentation/html
	@rm -rf documentation/rest/index.rest

gen-man: check-builddep predoc clean-man

	@echo "Generating documentation from source [RST > MAN]"
	@rst2man --language="en" --title="AGUILAS" documentation/man/aguilas.rest documentation/man/aguilas.1

gen-conf: check-builddep clean-conf

	@echo "Filling up configuration"
	@bash tools/gen-conf.sh
	@echo "Configuration file generated!"

clean: clean-img clean-mo clean-man clean-conf clean-predoc

clean-all: clean-img clean-mo clean-html clean-wiki clean-man clean-conf clean-predoc

clean-predoc:

	@echo "Cleaning preprocessed documentation files ..."
	@bash tools/clean-predoc.sh
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
		rm -rf $${LOCALE}/messages.mo; \
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

install: copy config

config: 

	@mkdir -p $(DESTDIR)/var/www/
	@ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	@php -f setup/install.php
	@echo "AGUILAS configured and running!"

copy:

	@mkdir -p $(DESTDIR)/usr/share/aguilas/setup/
	@mkdir -p $(DESTDIR)/var/log/aguilas/
	@mkdir -p $(DESTDIR)/usr/share/man/man1/
	@mkdir -p $(DESTDIR)/usr/share/doc/aguilas/

	@# Installing application
	@cp -r locale libraries themes $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 $(PHPS) $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 setup/config.* $(DESTDIR)/usr/share/aguilas/setup/

	@# Installing logfiles
	@install -D -m 644 $(LOGS) $(DESTDIR)/var/log/aguilas/
	@chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/

	@# Installing manpage
	@install -D -m 644 documentation/man/aguilas.1 $(DESTDIR)/usr/share/man/man1/

	@# Installing documentation
	@cp -r documentation/html/* $(DESTDIR)/usr/share/doc/aguilas/

	@# Removing unnecesary svg's
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			rm -rf $(DESTDIR)/usr/share/aguilas/themes/$${THEME}/images/$${IMAGE}.svg; \
		done; \
		rm -rf themes/$${THEME}/images/favicon.png; \
	done
	@echo "Files copied"

uninstall:

	@php -f setup/uninstall.php
	@rm -rf $(DESTDIR)/usr/share/aguilas/
	@rm -rf $(DESTDIR)/var/log/aguilas/
	@rm -rf $(DESTDIR)/var/www/aguilas/
	@echo "Uninstalled"

release-all: release buildpackage

release:

	@bash tools/release.sh

buildpackage:

	@bash tools/buildpackage.sh

snapshot: gen-html gen-wiki clean

	@bash tools/snapshot.sh

reinstall: uninstall install
