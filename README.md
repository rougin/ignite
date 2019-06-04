# Ignite

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Total Downloads][ico-downloads]][link-downloads]

Ignite is yet another [Codeigniter](https://codeigniter.com) application with a different approach on folder structure:

* Instead of the default `application` directory, it is now the root directory
* Removed the `defined('BASEPATH') OR exit('No direct script access allowed');` line of code
* The `index.php` in the root directory is moved in the `web` directory for security purposes
* The `user_guide` directory is also moved to the `web` directory

## Installation

Install `Ignite` via [Composer](https://getcomposer.org/):

``` bash
$ composer create-project rougin/ignite "acme"
```

## Folder Structure

```
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
│   ├── user_guide
│   ├── .htaccess
│   └── index.php
└── composer.json
```

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