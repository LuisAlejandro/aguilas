# Makefile

SHELL = sh -e

IMAGES = $(shell ls themes/canaima/images/ | grep ".svg" | sed 's/.svg//g')
THEMES = $(shell ls themes/)
LOCALES = $(shell ls locale/)
PHPS = $(wildcard *.php)
LOGS = $(wildcard logs/*.log)

CONVERT = $(shell which convert)
BINBASH = $(shell which bash)
RST2MAN = $(shell which rst2man)
ICOTOOL = $(shell which icotool)
SPHINX = $(shell which sphinx-build)
MSGFMT = $(shell which msgfmt)
IMVERSION = $(shell ls /usr/lib/ | grep -i "imagemagick" | sed -n 1p)
LIBSVG = /usr/lib/$(IMVERSION)/modules-Q16/coders/svg.so

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

	@touch check-builddep

gen-img: check-builddep clean-img

	@printf "Generating images from source [SVG > PNG,ICO] ["
	@for THEME in $(THEMES); do \
		for IMAGE in $(IMAGES); do \
			convert themes/$${THEME}/images/$${IMAGE}.svg themes/$${THEME}/images/$${IMAGE}.png; \
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

gen-doc: check-builddep clean-doc

	@echo "Generating documentation from source [RST > HTML,MAN]"
	@make -C docs html
	@rst2man --language="en" --title="AGUILAS" docs/man-aguilas.rst docs/aguilas.1
	@touch gen-doc

gen-conf: check-builddep clean-conf

	@echo "Filling up configuration"
	@bash scripts/gen-conf.sh
	@touch gen-conf

clean: clean-all

clean-all: clean-img clean-mo clean-doc clean-conf

distclean: clean

	@rm -rf check-builddep

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

clean-doc:

	@echo "Cleaning generated documentation"
	@rm -rf docs/_build
	@rm -rf docs/aguilas.1
	@rm -rf gen-doc

clean-conf:

	@echo "Cleaning generated configuration"
	@rm -rf config.php var com
	@rm -rf gen-conf

config: install

	@mkdir -p $(DESTDIR)/var/www/
	@ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	@php -f install.php

install: gen-img gen-mo

	@install -D locale themes $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 $(PHPS) $(DESTDIR)/usr/share/aguilas/
	@install -D -m 644 $(LOGS) $(DESTDIR)/var/log/aguilas/
	@chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/
	@touch install

uninstall:

	@rm -rf $(DESTDIR)/usr/share/aguilas/
	@rm -rf $(DESTDIR)/var/log/aguilas/
	@rm -rf $(DESTDIR)/var/www/aguilas/
	@echo "Uninstalled"
	
release:

	@bash scripts/release.sh

reinstall: uninstall install
