FROM ubuntu:16.04

RUN apt-get update && \
    apt-get install -y apt-utils php php-mysql php-intl php-xml php-fpm php-cli php-mbstring git unzip vim curl

COPY ./docker/www.conf /etc/php/7.0/fpm/pool.d/www.conf

WORKDIR /var

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    usermod -u 1000 www-data

WORKDIR /var/www/feedpaper/

EXPOSE 9000