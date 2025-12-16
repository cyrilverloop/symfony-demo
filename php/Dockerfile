# PHP-FPM :
FROM php:8.5.0-fpm-alpine

RUN apk update \
    && apk add --no-cache git linux-headers tzdata gnupg icu-dev icu-data-full oniguruma-dev sqlite-dev libzip-dev $PHPIZE_DEPS \
    && docker-php-ext-install intl mbstring pdo pdo_mysql pdo_sqlite mysqli pcntl posix zip \
    && docker-php-ext-enable intl mbstring pdo pdo_mysql pdo_sqlite mysqli pcntl posix zip \
    && apk del --purge $PHPIZE_DEPS

# Adding PIE :
COPY --from=ghcr.io/php/pie:bin /pie /usr/bin/pie

# Adding XDEBUG :
RUN apk add --no-cache php85-dev g++ make autoconf libtool bison re2c $PHPIZE_DEPS \
    && pie install xdebug/xdebug:3.5.0 \
    && rm /usr/bin/pie \
    && apk del --purge $PHPIZE_DEPS

# Copying PHP configuration :
COPY ./conf/ /usr/local/etc/

# Adding Composer :
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Adding Phive :
RUN wget -O phive.phar https://phar.io/releases/phive.phar \
    && wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc \
    && gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 \
    && gpg --verify phive.phar.asc phive.phar \
    && chmod +x phive.phar \
    && mv phive.phar /usr/local/bin/phive

# Adding a user :
ARG uid
ARG user
RUN adduser -g "" -D -h "/var/www/" -u "$uid" "$user"
