#!/usr/bin/env bash

service php7.0-fpm start

composer install
wait

php app/console doctrine:schema:update --force
wait

tail -f /var/www/feedpaper/app/logs/dev.log