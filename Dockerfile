FROM ubuntu:16.04

RUN apt-get update && \
    apt-get install -y apt-utils php php-mysql php-intl php-xml php-fpm php-cli php-mbstring git unzip vim

COPY ./docker/www.conf /etc/php/7.0/fpm/pool.d/www.conf

WORKDIR /var

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
		php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
		php composer-setup.php && \
		php -r "unlink('composer-setup.php');" && \
    usermod -u 1000 www-data

WORKDIR /var/www/feedpaper/

EXPOSE 9000