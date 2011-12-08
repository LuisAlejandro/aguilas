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

LOGS :=		"ChangePasswordDo" \
		"ResetPasswordMail" \
		"ResetPasswordDo" \
		"ResendMailDo" \
		"NewUserMail" \
		"DeleteUserDo" \
		"NewUserDo"

all: build

test:

	@echo "Nada para hacer"

config:

	bash config.sh       

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
		icotool -c -o themes/$${THEME}/images/favicon.ico themes/$${THEME}/images/favicon.png; \
		convert themes/$${THEME}/images/banner.png themes/$${THEME}/images/banner.jpg; \
	done

install:

	mkdir -p $(DESTDIR)/usr/share/aguilas/
	mkdir -p $(DESTDIR)/var/log/aguilas/
	mkdir -p $(DESTDIR)/var/www/
	cp -r locale themes $(DESTDIR)/usr/share/aguilas/
	cp -r *.php $(DESTDIR)/usr/share/aguilas/
	ln -s $(DESTDIR)/usr/share/aguilas /var/www/aguilas
	
	for LOG in $(LOGS); \
	do \
		touch $(DESTDIR)/var/log/aguilas/$${LOG}.log; \
	done \

	chown -R www-data:www-data $(DESTDIR)/var/log/aguilas/

uninstall:

	rm -rf $(DESTDIR)/usr/share/aguilas/
	rm -rf $(DESTDIR)/var/log/aguilas/
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
