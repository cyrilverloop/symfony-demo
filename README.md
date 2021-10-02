# symfony-demo

This is a simple Symfony 5.3 demo project using PHP 8.0+ and nodejs 16+.

[![License](https://img.shields.io/github/license/cyrilverloop/symfony-demo)](https://github.com/cyrilverloop/symfony-demo/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/symfony-demo/coverage.svg)](https://shepherd.dev/github/cyrilverloop/symfony-demo)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D8-%23777BB4?logo=php&style=flat)](https://www.php.net/)


## Installation

Downloading the project :

```shellsession
user@host ~$ cd [PATH_WHERE_TO_PUT_THE_PROJECT] # E.g. ~/projects/
user@host projects$ git clone https://github.com/cyrilverloop/symfony-demo.git
user@host projects$ cd symfony-demo
```

This demo uses 2 Docker containers.
One with the `php:apache` image and the other with the `mariadb` image.
You need to define a mariadb root password in the `./mariadb/.password` file (see `./mariadb/.password.dist` example).
Then, you can make these containers up and running with one docker compose command :
```shellsession
user@host symfony-demo$ docker compose up
```
You can execute any command for your app through :
```shellsession
user@host symfony-demo$ docker compose exec app [COMMAND]
```
Alternatively, you can access the `app` container and execute your commands once inside :
```shellsession
user@host symfony-demo$ docker exec -it symfony-demo-app-1 bash
```
The following commands are executed outside the containers.

Define the database configuration (see `.env` or `.env.local.dist`).

### Create the database

The "-e test" option is to be use for the test environment.
```shellsession
user@host symfony-demo$ docker compose exec app ./bin/console doctrine:database:create [-e test]
user@host symfony-demo$ docker compose exec app ./bin/console doctrine:migrations:migrate [--no-interaction] [-e test]
```

### For production

```shellsession
user@host symfony-demo$ docker compose exec app composer install -o --no-dev
user@host symfony-demo$ docker compose exec app npm i
user@host symfony-demo$ docker compose exec app npm run build
```

### For development / test

```shellsession
user@host symfony-demo$ docker compose exec app composer install -o
user@host symfony-demo$ docker compose exec app npm i
user@host symfony-demo$ docker compose exec app npm run dev
user@host symfony-demo$ docker compose exec app phive install
```

Now, the demo will be available in your browser through : http://127.0.0.1/index.php/

To stop the containers :
```shellsession
user@host symfony-demo$ docker compose down
```


## Tests

To run the tests you need to create the database with the test environment which uses Sqlite.
```shellsession
user@host symfony-demo$ docker compose exec app ./tools/phpunit -c build/phpunit.xml
```
