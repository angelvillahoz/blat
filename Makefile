BLAT = docker-compose exec blat_server blat
PHP = docker-compose exec php_server php
PHP_BASH = docker-compose exec php_server

.PHONY: analyze build docker docker-restart lint logs php-libraries-reload vendor-install vendor-update xdebug

help:
	@ echo "Usage: make [target]"
	@ echo "  xdebug                              Activate the XDebug module for PHP debugging."

analyze: vendor
	$(PHP) ./vendor/bin/phpstan analyse -l 7 -c phpstan.neon lib

build: vendor
	cd ./go/blatserver/assets && sh ./download-external-assets.sh

database-backup:
	docker build -t mariadb_database_backup ./utilities/mariadb/database-backup/
	docker run --rm -tv $(PWD)/dumps:/data/dumps --user $$(id -u):$$(id -g) --env-file ./.env --network redfly_default mariadb_database_backup

docker:
	docker-compose down -v
	docker-compose build
	docker-compose up -d

docker-restart:
	docker-compose down -v
	docker-compose up -d

lint: vendor
	$(PHP) ./vendor/bin/phpcbf | true
	$(PHP) ./vendor/bin/phpcs

logs:
	mkdir -p ./logs

php-libraries-reload: composer.json
	docker pull composer:latest
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer --version
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer validate
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer dump-autoload -o

schema-backup:
	docker build -t mariadb_schema_backup ./utilities/mariadb/schema-backup/
	docker run --rm -tv $(PWD)/db:/data/db --user $$(id -u):$$(id -g) --env-file ./.env --network redfly_default mariadb_schema_backup

vendor-install: composer.json
	[ -f composer.lock ] && rm composer.lock || true
	docker pull composer:latest
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer --version
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer validate
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer install

vendor-update: composer.json
	[ -f composer.lock ] && rm composer.lock || true
	docker pull composer:latest
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer --version
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer validate
	docker run --rm --volume $(PWD):/app --user $$(id -u):$$(id -g) composer update

xdebug:
	$(PHP_BASH) cp ./assets/xdebug.ini.dist /usr/local/etc/php/conf.d/xdebug.ini
	$(PHP_BASH) chmod 0644 /usr/local/etc/php/conf.d/xdebug.ini
	$(PHP_BASH) sed -i 's,<XDEBUG_IP_ADDRESS>,$(XDEBUG_IP_ADDRESS),g' /usr/local/etc/php/conf.d/xdebug.ini
	docker-compose restart php_server
