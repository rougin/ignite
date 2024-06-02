# Ignite

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Total Downloads][ico-downloads]][link-downloads]

Ignite is yet another [Codeigniter](https://codeigniter.com) application with a different approach on folder structure:

* Moved the `application` directory as the root directory; and
* Created a `web` directory to store the main `index.php` file.

## Installation

Install `Ignite` via [Composer](https://getcomposer.org/):

``` bash
$ composer create-project rougin/ignite "acme"
```

## Folder Structure

``` bash
acme/
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
├── third_party/
├── vendor/
├── views/
├── web/
│   ├── .htaccess
│   └── index.php
└── composer.json
```

**NOTE**: Codeigniter's documentation can be found in this [link](https://codeigniter.com/userguide3/).

## Run PHP built-in server (PHP 5.4 or later)

``` bash
php -S localhost:8000 -t web/
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

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