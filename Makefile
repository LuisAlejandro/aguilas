# Makefile

SHELL := sh -e

all: build

test:

	@echo "Nada para hacer"

build:

	@echo "Nada para hacer"

install:

	mkdir -p $(DESTDIR)/usr/share/aguilas/
	mkdir -p $(DESTDIR)/var/www/
	cp -r logs images css js $(DESTDIR)/usr/share/aguilas/
	cp -r *.php $(DESTDIR)/usr/share/aguilas/
	cp -r AUTHORS README THANKS TODO COPYING LICENSE $(DESTDIR)/usr/share/aguilas/
	touch $(DESTDIR)/usr/share/aguilas/logs/nuevo_usuario.log
	touch $(DESTDIR)/usr/share/aguilas/logs/ajax.log
	touch $(DESTDIR)/usr/share/aguilas/logs/cambio_password.log
	touch $(DESTDIR)/usr/share/aguilas/logs/eliminar_usuario.log
	ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	chown -R www-data:www-data $(DESTDIR)/usr/share/aguilas/logs/
	
uninstall:

	rm -rf $(DESTDIR)/usr/share/aguilas/
	rm -rf $(DESTDIR)/var/www/aguilas/

clean:

distclean:

reinstall: uninstall install
