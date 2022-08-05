# symfony-demo

This is a simple Symfony 6.1 demo project using PHP 8.1 and NodeJs 18.

[![License](https://img.shields.io/github/license/cyrilverloop/symfony-demo)](https://github.com/cyrilverloop/symfony-demo/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/symfony-demo/coverage.svg)](https://shepherd.dev/github/cyrilverloop/symfony-demo)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D8.1-%23777BB4?logo=php&style=flat))](https://www.php.net/)


## Installation

Downloading the project :

```shellsession
user@host ~$ cd [PATH_WHERE_TO_PUT_THE_PROJECT] # E.g. ~/projects/
user@host projects$ git clone https://github.com/cyrilverloop/symfony-demo.git
user@host projects$ cd symfony-demo
```

This demo uses 4 Docker images based on :
1. `mariadb` to run the database;
2. `php:apache` to run the web server;
3. `composer` to install PHP dependencies;
4. `node:alpine` to install node dependencies.

### Building the image

Define a mariadb root password in the `./mariadb/.password` file (see `./mariadb/.password.dist` example)
and build the app image :
```shellsession
user@host symfony-demo$ docker compose build
```

### Installing PHP dependencies
Define the database configuration for Symfony (see `./app/.env` or `./app/.env.local.dist`)
and install the PHP dependencies :
```shellsession
user@host symfony-demo$ docker compose run --rm app composer install -o [--no-dev]
```
The "--no-dev" option is for the production environment.

For the development and the test environments only :
```shellsession
user@host symfony-demo$ docker compose run --rm app phive install --trust-gpg-keys 4AA394086372C20A,12CE0F1D262429A5,31C7E470E2138192,67F861C3D889C656
```

### Creating the database


```shellsession
user@host symfony-demo$ docker compose run --rm app ./bin/console doctrine:database:create [-e test]
user@host symfony-demo$ docker compose run --rm app ./bin/console doctrine:migrations:migrate [--no-interaction] [-e test]
```
The "-e test" option is to for the test environment which uses Sqlite.


### Building assets

Install node dependencies and build the assets :
```shellsession
user@host symfony-demo$ docker compose run --rm node npm i
user@host symfony-demo$ docker compose run --rm node npm run build
```

## Usage

Once the installation is complete, you can start the containers with :
```shellsession
user@host symfony-demo$ docker compose up -d
```

The demo will be available in your browser through : http://127.0.0.1/index.php/

To stop the containers :
```shellsession
user@host symfony-demo$ docker compose down
```


## Tests

First, you need to [configure the app](#installing-php-dependencies)
and [create the database](#creating-the-database) for the test environment.
Then, run the tests :
```shellsession
user@host symfony-demo$ docker compose run --rm app ./tools/phpunit -c ./ci/phpunit.xml
```
The generated outputs will be in `./ci/phpunit/`.

And, run the mutation tests :
```shellsession
user@host symfony-demo$ docker compose run --rm app ./tools/infection -c ./ci/infection.json
```
The generated outputs will be in `./ci/infection/`.


## PHPDoc

To generate the PHPDoc, use this command after [installing phive dependencies](#installing-php-dependencies) :
```shellsession
user@host symfony-demo$ docker compose run --rm app ./tools/phpDocumentor --config ./ci/phpdoc.xml
```
The generated HTML documentation will be in `./app/ci/phpdoc/`.
