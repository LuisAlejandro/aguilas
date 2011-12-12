
INSTALL=install -m 0644
INSTALL_DIR=install -d
IMAGES=$(wildcard themes/canaima/images/*.svg)
THEMES=$(wildcard themes/*)
PHP=$(wildcard *.php)
LOGS=$(wildcard logs/*.log)


all: gen-img data

gen-img:

	$(MAKE) clean-img
	@for THEME in $(THEMES); do \
		@for IMAGE in $(IMAGES); do \
			@convert themes/$${THEME}/images/$${IMAGE}.svg themes/$${THEME}/images/$${IMAGE}.png; \
			@echo "Generating images from source [SVG > PNG]"; \
			@printf "."; \
		done; \
		icotool -c -o themes/$${THEME}/images/favicon.ico themes/$${THEME}/images/favicon.png; \
	done

doc:

	$(MAKE) clean-doc
        rst2man --language="en" --title="AGUILAS" docs/man-aguilas.rst docs/aguilas.1
	$(MAKE) -C docs latex
	$(MAKE) -C docs html
	$(MAKE) -C docs/_build/latex all-pdf

data:

	$(MAKE) clean-data
	@bash scripts/pre-config.sh

install:

	mkdir -p $(DESTDIR)/usr/share/aguilas/
	mkdir -p $(DESTDIR)/var/log/aguilas/
	$(INSTALL_DIR) locale themes $(DESTDIR)/usr/share/aguilas/
	$(INSTALL) $(PHP) $(DESTDIR)/usr/share/aguilas/
	$(INSTALL) $(LOGS) $(DESTDIR)/var/log/aguilas/
	chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/

config:

	mkdir $(DESTDIR)/var/www/
	ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	php -f install.php

uninstall:

	rm -rf $(DESTDIR)/usr/share/aguilas/
	rm -rf $(DESTDIR)/var/log/aguilas/
	rm -rf $(DESTDIR)/var/www/aguilas/

clean-img:

	for THEME in $(THEMES); \
	do \
		rm -rf themes/$${THEME}/images/*.png; \
		rm -rf themes/$${THEME}/images/*.jpg; \
		rm -rf themes/$${THEME}/images/*.ico; \
	done \

clean-data:

	rm -rf config.php var com

clean-doc:

	$(MAKE) -C docs clean
	rm -rf docs/_build
	rm -rf docs/aguilas.1
	
reinstall: uninstall install
