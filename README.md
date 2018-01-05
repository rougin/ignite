# Ignite

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Ignite is yet another [Codeigniter](https://codeigniter.com) application with a different approach on folder structure:

* Instead of the default `application` directory, it is now the root directory
* Removed `defined('BASEPATH') OR exit('No direct script access allowed');` line of code
* The `index.php` in the root directory is moved in the `web` directory for security purposes
* The `user_guide` directory is also moved to the `web` directory

## Install

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

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/ignite.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/ignite.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/ignite
[link-downloads]: https://packagist.org/packages/rougin/ignite
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors