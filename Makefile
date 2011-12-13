INSTALL=install -m 0644
INSTALLDIR=install -d
IMAGES=$(shell ls themes/canaima/images/ | grep ".svg" | sed 's/.svg//g')
THEMES=$(shell ls themes/)
PHPS=$(wildcard *.php)
LOGS=$(wildcard logs/*.log)
CONVERT=$(shell which convert)
RST2MAN=$(shell which rst2man)
ICOTOOL=$(shell which icotool)
SPHINX=$(shell which sphinx-build)

all: gen-img gen-config

build: gen-img gen-doc

build-all: gen-img gen-doc gen-config

gen-img: clean-img

	@if [ ! -e check-builddep ]; then \
		$(MAKE) check-builddep; \
	fi

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

gen-doc: clean-doc 

	@if [ ! -e check-builddep ]; then \
		$(MAKE) check-builddep; \
	fi

	$(MAKE) -C docs html
        @rst2man --language="en" --title="AGUILAS" docs/man-aguilas.rst docs/aguilas.1

	@touch doc

gen-conf: clean-conf

	@bash scripts/pre-config.sh
	@mkdir $(DESTDIR)/var/www/
	@ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	@php -f install.php

	@touch data

clean: clean-img clean-doc clean-conf

clean-all: clean-img clean-doc clean-conf

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

clean-doc:

	$(MAKE) -C docs clean
	@rm -rf docs/_build
	@rm -rf docs/aguilas.1

clean-conf:

	@rm -rf config.php var com

install:

	@if [ -e gen-img ] && [ -e gen-config ]; then \
		mkdir -p $(DESTDIR)/usr/share/aguilas/; \
		mkdir -p $(DESTDIR)/var/log/aguilas/; \
		$(INSTALLDIR) locale themes $(DESTDIR)/usr/share/aguilas/; \
		$(INSTALL) $(PHPS) $(DESTDIR)/usr/share/aguilas/; \
		$(INSTALL) $(LOGS) $(DESTDIR)/var/log/aguilas/; \
		chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/; \
	else
		@echo "You must run 'make gen-img' and 'make data' before you can install."
	fi

uninstall:

	@rm -rf $(DESTDIR)/usr/share/aguilas/
	@rm -rf $(DESTDIR)/var/log/aguilas/
	@rm -rf $(DESTDIR)/var/www/aguilas/
	@echo "Uninstalled"

reinstall: uninstall install
