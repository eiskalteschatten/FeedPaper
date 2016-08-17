#!/usr/bin/env bash

service php7.0-fpm start

php /var/composer.phar install
wait

#php app/console doctrine:database:create
#wait

#php app/console doctrine:schema:update --force
#wait

tail -f /dev/null