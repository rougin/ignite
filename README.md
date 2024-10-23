# Ignite

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Total Downloads][ico-downloads]][link-downloads]

Ignite is yet another [Codeigniter 3](https://codeigniter.com/userguide3) project template with a twist:

* Moved the `application` directory as the root directory; and
* Created a `public` directory to store the main `index.php` file.

``` bash
ciacme/
├── cache/
├── config/
├── controllers/
├── core/
├── helpers/
├── hooks/
├── language/
├── libraries/
├── logs/
├── models/
├── public/
│   ├── .htaccess
│   └── index.php
├── third_party/
├── vendor/
├── views/
└── composer.json
```

## Installation

Use [Composer](https://getcomposer.org/) to create a new `Ignite`-based `Codeigniter 3` project:

``` bash
$ composer create-project rougin/ignite "ciacme"
```

## Running in local

To run the application for development, the [built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php) of PHP can be used:

``` bash
$ cd ciacme
$ php -S localhost:4464 -t public/
```

> [!NOTE]
> The built-in web server is only available for PHP versions `v5.4` and higher.

After running the web server, access `http://localhost:4464` in a web browser.

## Featured packages

`Ignite` also provides default configuration for the following third-party packages below. These packages improve quality of development when writing code under `Codeigniter 3`:

* [Combustor](https://roug.in/combustor/)

Combustor is a utility package for `Codeigniter 3` that generates controllers, models, and views based on the provided database tables. It uses the [Describe](https://roug.in/describe/) package for getting columns from a database table and as the basis for code generation.

* [Refinery](https://roug.in/refinery/)

Refinery is a console-based package of [Migrations Class](https://www.codeigniter.com/userguide3/libraries/migration.html) for `Codeigniter 3`. It also uses the [Describe](https://roug.in/describe/) package for retrieving the database tables for creating database migrations.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/ignite.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/ignite.svg?style=flat-square

[link-changelog]: https://github.com/rougin/ignite/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/ignite/contributors
[link-downloads]: https://packagist.org/packages/rougin/ignite
[link-license]: https://github.com/rougin/ignite/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/ignite