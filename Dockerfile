FROM php:8.1.16-apache

WORKDIR /var/www
COPY . /var/www

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions opcache intl gd mbstring exif zip

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

COPY ./.apache/vhosts.conf /etc/apache2/sites-available/000-default.conf

RUN chmod -R 777 /var/www/data

CMD apachectl -D FOREGROUND