# PHP-FPM :
FROM php:8.5-fpm-alpine

RUN apk update \
    && apk add --no-cache git linux-headers tzdata gnupg icu-dev icu-data-full libpq-dev oniguruma-dev sqlite-dev libzip-dev $PHPIZE_DEPS \
    && docker-php-ext-install intl mbstring pdo pdo_mysql pdo_pgsql pdo_sqlite mysqli pcntl posix \
    && docker-php-ext-enable intl mbstring pdo pdo_mysql pdo_pgsql pdo_sqlite mysqli pcntl posix \
    && apk del --purge $PHPIZE_DEPS

# Adding PIE :
COPY --from=ghcr.io/php/pie:bin /pie /usr/bin/pie

# Adding XDEBUG :
RUN apk add --no-cache php85-dev g++ make autoconf libtool bison re2c $PHPIZE_DEPS \
    && pie install xdebug/xdebug:3.5.0 \
    && apk del --purge $PHPIZE_DEPS

# Adding MongoDB :
RUN apk add --no-cache libbson mongo-c-driver $PHPIZE_DEPS \
    && pie install mongodb/mongodb-extension \
    && apk del --purge $PHPIZE_DEPS

# Removing PIE :
RUN rm /usr/bin/pie

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
