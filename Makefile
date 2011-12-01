# Makefile

SHELL := sh -e

IMAGES :=	"captcha" \
		"editar" \
		"eliminar" \
		"favicon" \
		"fondoforma" \
		"listar" \
		"logo" \
		"nuevo" \
		"olvidar" \
		"password"

THEMES :=	"debian" \
		"canaima"

all: build

test:

	@echo "Nada para hacer"

build:

	for THEME in $(THEMES); \
	do \
		for IMAGE in $(IMAGES); \
		do \
			convert themes/$${THEME}/images/$${IMAGE}.svg themes/$${THEME}/images/$${IMAGE}.png; \
		done \
	done \

	for THEME in $(THEMES); \
	do \
		xcf2png -o themes/$${THEME}/images/banner.png themes/$${THEME}/images/banner.xcf; \
		icotool -c -o themes/$${THEME}/images/favicon.ico themes/$${THEME}/images/favicon.png; \
		convert themes/$${THEME}/images/banner.png themes/$${THEME}/images/banner.jpg; \
	done


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

	for THEME in $(THEMES); \
	do \
		rm -rf themes/$${THEME}/images/*.png; \
		rm -rf themes/$${THEME}/images/*.jpg; \
		rm -rf themes/$${THEME}/images/*.ico; \
	done

distclean:

reinstall: uninstall install
