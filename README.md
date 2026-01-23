# symfony-demo

This is a simple Symfony 8.0 demo project using PHP 8.5.

**This demo is using AssetMapper. To see a version with Encore, switch to the unmaintained `webpack-encore` branch.**

[![License](https://img.shields.io/github/license/cyrilverloop/symfony-demo)](https://github.com/cyrilverloop/symfony-demo/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/symfony-demo/coverage.svg)](https://shepherd.dev/github/cyrilverloop/symfony-demo)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D8.5-%23777BB4?logo=php&style=flat)](https://www.php.net/)

## Installation

Downloading the project :

```shellsession
user@host ~$ cd [PATH_WHERE_TO_PUT_THE_PROJECT] # E.g. ~/projects/
user@host projects$ git clone https://github.com/cyrilverloop/symfony-demo.git
user@host projects$ cd symfony-demo
```

This demo uses Docker images based on :
1. `mariadb` for the MariaDB database;
2. `postgres` for the PostgreSQL database;
3. `httpd:alpine` for the web server;
4. `php:8.5.0-fpm-alpine` for php files;
5. `alpine/openssl` to generate a TLS certificate.

The `app` (php) container depends on the `mariadb`, `postgres` and `httpd` containers.
After each `docker compose run --rm php ...` command,
Docker will not remove the dependencies (`mariadb`, `postgres`, `httpd` and network).
You can remove them with :
```shellsession
user@host symfony-demo$ docker compose down
```

### Create the cache directories (recommended)

It is recommended to use cache directories for the downloaded dependencies (Composer and Phive).
If you do not already have cache directories, create them with :
```shellsession
user@host symfony-demo$ mkdir -p ./.cache/composer/ ./.cache/phive/
```

Default paths are configured in `compose.override.yaml.dist`
and you can customise them in `compose.override.yaml`.

### Generate a certificate

Generate a TLS self-signed certificate with the following script :
```shellsession
user@host symfony-demo$ ./tls/create-self-signed-certificate.sh
```

### Building the images

Copy the example files :
```shellsession
user@host symfony-demo$ cp compose.override.yaml.dist compose.override.yaml
user@host symfony-demo$ cp ./.env.dist ./.env
user@host symfony-demo$ cp httpd/.ashrc.dist httpd/.ashrc
user@host symfony-demo$ cp php/.ashrc.dist php/.ashrc
```

Fill in the variables in `./.env`.
These files are in the `.gitignore` file and can be customised.

Build the images :
```shellsession
user@host symfony-demo$ docker compose build
```

### Installing PHP dependencies

Define the database configuration for Symfony (see `./app/.env` or `./app/.env.local.dist`)
and install the PHP dependencies :
```shellsession
user@host symfony-demo$ docker compose run --rm php composer install -o [--no-dev]
```
The "--no-dev" option is for the production environment.

For the development and the test environments only :
```shellsession
user@host symfony-demo$ docker compose run --rm php phive install --trust-gpg-keys 4AA394086372C20A,12CE0F1D262429A5,31C7E470E2138192,8AC0BAA79732DD42,C5095986493B4AA0
```

### Creating the databases

#### Development and production environments

For MariaDB :
```shellsession
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:database:create --connection=default
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:diff --configuration ./config/migrations/furniture.yaml
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:migrate --configuration ./config/migrations/furniture.yaml --no-interaction
```

For PostgreSQL :
```shellsession
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:database:create --connection=postgres
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:diff --configuration ./config/migrations/clothing.yaml
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:migrate --configuration ./config/migrations/clothing.yaml --no-interaction
```

#### Test environment

The test environment uses Sqlite instead of MariaDB and PostgreSQL.

```shellsession
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:diff --configuration ./config/migrations/furniture_test.yaml -e test
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:diff --configuration ./config/migrations/clothing_test.yaml -e test
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:migrate --configuration ./config/migrations/furniture_test.yaml --no-interaction -e test
user@host symfony-demo$ docker compose run --rm php ./bin/console doctrine:migrations:migrate --configuration ./config/migrations/clothing_test.yaml --no-interaction -e test
```

### Serving assets in production

```shellsession
user@host symfony-demo$ docker compose run --rm php bin/console asset-map:compile
```

## Usage

Once the installation is complete, you can start the containers with :
```shellsession
user@host symfony-demo$ docker compose up -d
```

The demo will be available in your browser through : https://localhost:8000/

To stop the containers :
```shellsession
user@host symfony-demo$ docker compose down
```

## Tests

First, you need to [configure the app](#installing-php-dependencies)
and [create the database](#creating-the-database) for the test environment.
Then, run the tests :
```shellsession
user@host symfony-demo$ docker compose run --rm php ./tools/phpunit -c ./ci/phpunit.xml
```
The generated outputs will be in `./app/ci/phpunit/`.

And, run the mutation tests :
```shellsession
user@host symfony-demo$ docker compose run --rm php ./tools/infection -c./ci/infection.json
```
The generated outputs will be in `./app/ci/infection/`.

## PHPDoc

To generate the PHPDoc, use this command after [installing phive dependencies](#installing-php-dependencies) :
```shellsession
user@host symfony-demo$ docker compose run --rm php ./tools/phpDocumentor --config ./ci/phpdoc.xml
```
The generated HTML documentation will be in `./app/ci/phpdoc/`.
