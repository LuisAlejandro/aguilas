# Makefile

SHELL = sh -e

IMAGES = $(shell ls themes/canaima/images/ | grep ".svg" | sed 's/.svg//g')
THEMES = $(shell ls themes/)
LOCALES = $(shell ls locale/)
PHPS = $(wildcard *.php)
LOGS = $(wildcard events/*.log)

CONVERT = $(shell which convert)
PHP = $(shell which php5)
PYTHON = $(shell which python)
BINBASH = $(shell which bash)
RST2MAN = $(shell which rst2man)
ICOTOOL = $(shell which icotool)
SPHINX = $(shell which sphinx-build)
MSGFMT = $(shell which msgfmt)
LIBSVG = $(shell find /usr/lib/ -maxdepth 1 -type d -iname "imagemagick-*")/modules-Q16/coders/svg.so
PHPLDAP = $(shell find /usr/lib/ -name "mysql.so" | grep "php5")
PHPMYSQL = $(shell find /usr/lib/ -name "ldap.so" | grep "php5")


all: gen-img gen-mo gen-conf clean-stamps

build: gen-img gen-mo gen-doc clean-stamps

build-all: gen-img gen-mo gen-doc gen-conf clean-stamps

check-builddep:

	@printf "Checking if we have bash... "
	@if [ -z $(BINBASH) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"bash\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have sphinx-build... "
	@if [ -z $(SPHINX) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python-sphinx\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have convert... "
	@if [ -z $(CONVERT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"imagemagick\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have rst2man... "
	@if [ -z $(RST2MAN) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python-docutils\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have msgfmt... "
	@if [ -z $(MSGFMT) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"gettext\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have icotool... "
	@if [ -z $(ICOTOOL) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"icoutils\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have imagemagick svg support... "
	@if [ -z $(LIBSVG) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"libmagickcore-extra\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have python... "
	@if [ -z $(PYTHON) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"python\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP... "
	@if [ -z $(PHP) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-cli\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP LDAP support... "
	@if [ -z $(PHPLDAP) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-ldap\" package."; \
		exit 1; \
	fi
	@echo

	@printf "Checking if we have PHP MYSQL support... "
	@if [ -z $(PHPMYSQL) ]; then \
		echo "[ABSENT]"; \
		echo "If you are using Debian, Ubuntu or Canaima, please install the \"php5-mysql\" package."; \
		exit 1; \
	fi
	@echo

	@touch check-builddep

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
	@touch gen-img

gen-mo: check-builddep clean-mo

	@printf "Generating translation messages from source [PO > MO] ["
	@for LOCALE in $(LOCALES); do \
		msgfmt locale/$${LOCALE}/LC_MESSAGES/messages.po -o locale/$${LOCALE}/LC_MESSAGES/messages.mo; \
		printf "."; \
	done
	@printf "]\n"
	@touch gen-mo

gen-doc: gen-html gen-wiki gen-man

gen-html: check-builddep clean-html

	@echo "Generating documentation from source [RST > HTML]"
	@sphinx-build -a -E -Q -b html -d documentation/html/doctrees documentation/rst documentation/html
	@touch gen-html

gen-wiki: check-builddep clean-wiki

	@echo "Generating documentation from source [RST > WIKI]"
	@cd tools && python googlecode-wiki.py
	@touch gen-wiki


gen-man: check-builddep clean-man

	@echo "Generating documentation from source [RST > MAN]"
	@rst2man --language="en" --title="AGUILAS" documentation/man/aguilas.rst documentation/man/aguilas.1
	@touch gen-man

gen-conf: check-builddep clean-conf

	@echo "Filling up configuration"
	@cd tools && bash gen-conf.sh
	@echo
	@echo "Configuration file generated!"
	@touch gen-conf

clean: clean-all

clean-all: clean-img clean-mo clean-html clean-wiki clean-man clean-conf clean-stamps

clean-stamps:

	@rm -rf gen-img gen-html gen-wiki gen-man gen-mo gen-conf check-builddep

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
	@rm -rf gen-img

clean-mo:

	@printf "Cleaning generated localization ["
	@for LOCALE in $(LOCALES); do \
		rm -rf locale/$${LOCALE}/LC_MESSAGES/messages.mo; \
		printf "."; \
	done
	@printf "]\n"
	@rm -rf gen-mo

clean-html:

	@echo "Cleaning generated html ..."
	@rm -rf documentation/html/*
	@rm -rf gen-html

clean-wiki:

	@echo "Cleaning generated wiki pages ..."
	@rm -rf documentation/wiki/*
	@rm -rf gen-wiki

clean-man:

	@echo "Cleaning generated man pages ..."
	@rm -rf documentation/man/aguilas.1
	@rm -rf gen-man

clean-conf:

	@echo "Cleaning generated configuration ..."
	@rm -rf setup/config.php var com
	@rm -rf gen-conf

install: copy config

config: 

	@mkdir -p $(DESTDIR)/var/www/
	@ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	@php -f setup/install.php
	@echo "AGUILAS configured and running!"

copy:

	@mkdir -p $(DESTDIR)/usr/share/aguilas/setup/
	@mkdir -p $(DESTDIR)/var/log/aguilas/
	@cp -r locale libraries themes $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 $(PHPS) $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 setup/config.php $(DESTDIR)/usr/share/aguilas/setup/
	@install -D -m 644 $(LOGS) $(DESTDIR)/var/log/aguilas/
	@chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/
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

release:

	@cd tools && bash release.sh

snapshot:

	@cd tools && bash snapshot.sh

reinstall: uninstall install
