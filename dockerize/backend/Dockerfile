FROM php:7.4-fpm
RUN apt-get -y update \
        && apt-get install -y libicu-dev \
        && pecl install xdebug-2.8.1 \
        && docker-php-ext-enable xdebug \
        && docker-php-ext-configure intl \
        && docker-php-ext-install intl pdo pdo_mysql \
        && curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/composer