FROM php:apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && apt update && apt install -y --no-install-recommends git gnupg libicu-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "\nxdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && curl -L https://phar.io/releases/phive.phar > /usr/local/bin/phive \
    && chmod +x /usr/local/bin/phive

COPY --from=composer /usr/bin/composer /usr/bin/composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
