# Laravel Ethereum Client

[![Latest Stable Version](https://poser.pugx.org/ranabd36/larathereum/v/stable)](https://packagist.org/packages/ranabd36/larathereum)
[![Latest Unstable Version](https://poser.pugx.org/ranabd36/larathereum/v/unstable)](https://packagist.org/packages/ranabd36/larathereum)
[![License](https://poser.pugx.org/ranabd36/larathereum/license)](https://packagist.org/packages/ranabd36/larathereum)
[![Total Downloads](https://poser.pugx.org/ranabd36/larathereum/downloads)](https://packagist.org/packages/ranabd36/larathereum)

Laravel ethereum client with ERC20 (smart contract) support. 

## Installation

You can install the package via composer:

```bash
composer require ranabd36/larathereum
```

## Usage
After installing the package run the following commend to generate `larathereum.php` config file. Here you have to define ethereum node url and port.  
```
php artisan vendor:publish --provider=Larathereum\LarathereumServiceProvider
```

## Documentation
Link of the documentation will update very soon. 


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

To contribute you can create a pull request. But please check all functionality are working before creating pull request. We will publish your name on contribution list. 

### Security

If you discover any security related issues, please email codemenorg@gmail.com instead of using the issue tracker.

## Credits

- [Ibrahim Al Naz Rana](https://github.com/ranabd36)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
