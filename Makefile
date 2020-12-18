PHP_BASH = docker-compose exec php_server

.PHONY: xdebug

help:
	@ echo "Usage: make [target]"
	@ echo "  xdebug                              Activate the XDebug module for PHP debugging."

xdebug:
	$(PHP_BASH) cp ./assets/xdebug.ini.dist /usr/local/etc/php/conf.d/xdebug.ini
	$(PHP_BASH) chmod 0644 /usr/local/etc/php/conf.d/xdebug.ini
	$(PHP_BASH) sed -i 's,<XDEBUG_IP_ADDRESS>,$(XDEBUG_IP_ADDRESS),g' /usr/local/etc/php/conf.d/xdebug.ini
	docker-compose restart php_server
