# symfony-demo

This is a simple Symfony 5.2 demo project testing on PHP 8.0+ and nodejs 15+.

[![License](https://img.shields.io/github/license/cyrilverloop/symfony-demo)](https://github.com/cyrilverloop/symfony-demo/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/symfony-demo/coverage.svg)](https://shepherd.dev/github/cyrilverloop/symfony-demo)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D7.4-%23777BB4?logo=php&style=flat)](https://www.php.net/)


## Installation

Downloading the project :

```shellsession
user@host ~$ cd [PATH_WHERE_TO_PUT_THE_PROJECT] # E.g. ~/projects/
user@host projects$ git clone https://github.com/cyrilverloop/symfony-demo.git
```

### For production :

```shellsession
user@host projects$ cd symfony-demo
user@host symfony-demo$ composer install -o --no-dev
user@host symfony-demo$ npm i
user@host symfony-demo$ npm run build
```

### For development / test :

```shellsession
user@host projects$ cd symfony-demo
user@host symfony-demo$ composer install -o
user@host symfony-demo$ npm i
user@host symfony-demo$ npm run dev
user@host symfony-demo$ phive install
```

### To create the database :

First you must configure your database (see `./.env`).
The "-e test" option is to be use for the test environment.
```shellsession
user@host symfony-demo$ ./bin/console doctrine:database:create [-e test]
user@host symfony-demo$ ./bin/console doctrine:migrations:migrate [--no-interaction] [-e test]
```

### To run tests :

To run the tests you need to create the database. You can use the test environment which uses Sqlite.
```shellsession
user@host symfony-demo$ ./tools/phpunit -c build/phpunit.xml
```
