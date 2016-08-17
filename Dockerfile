FROM ubuntu:16.04

RUN apt-get update && \
    apt-get install -y apt-utils php php-mysql php-intl php-xml php-fpm php-cli php-mbstring git unzip vim

COPY ./docker/www.conf /etc/php/7.0/fpm/pool.d/www.conf

WORKDIR /var

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    usermod -u 1000 www-data

WORKDIR /var/www/feedpaper/

EXPOSE 9000