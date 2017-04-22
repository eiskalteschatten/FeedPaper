#!/usr/bin/env bash

service php7.0-fpm start

composer install
wait

php app/console doctrine:schema:update --force
wait

ln -sf ${PWD}/vendor/twbs/bootstrap web/resources/bootstrap
wait

php app/console assets:install --symlink web
wait

php app/console server:start 0.0.0.0:8000 --force
wait

tail -f /var/www/feedpaper/app/logs/dev.log
