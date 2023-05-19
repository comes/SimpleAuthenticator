# This is a simple implementation of the google authenticator algorithm

[![Latest Version on Packagist](https://img.shields.io/packagist/v/comes/simpleauthenticator.svg?style=flat-square)](https://packagist.org/packages/comes/simpleauthenticator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/comes/simpleauthenticator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/comes/simpleauthenticator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/comes/simpleauthenticator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/comes/simpleauthenticator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/comes/simpleauthenticator.svg?style=flat-square)](https://packagist.org/packages/comes/simpleauthenticator)

The SimpleAuthenticator package provides a straightforward implementation of the Google Authenticator algorithm for Laravel applications. It enables easy generation of one-time passwords (OTPs) based on a provided secret key.

Read more about the Google Authenticator algorithm in the [RFC 6238](https://tools.ietf.org/html/rfc6238) or check wikipedia [TOTP](https://en.wikipedia.org/wiki/Time-based_One-time_Password_algorithm)

## Usage

To use the SimpleAuthenticator, create an instance of the SimpleAuthenticator class by providing the secret key. Then, you can call the `generate()` method to generate the one-time password.

```php
use Comes\SimpleAuthenticator\SimpleAuthenticator;

$secret = 'YOUR_SECRET_KEY';
$authenticator = new SimpleAuthenticator;
$oneTimePassword = $authenticator->generate($secret);
```
## Laravel Integration

The SimpleAuthenticator package provides a Laravel command to generate OTPs. To use it, you need to publish the package configuration and add the secret keys to the configuration file.

```bash
php artisan vendor:publish --provider="Comes\SimpleAuthenticator\SimpleAuthenticatorServiceProvider" --tag="config"
```

After publishing the configuration, you can add your secret keys to the config/simpleauthenticator.php file. Then, you can use the `otp` command to generate an OTP for a specific app:

```bash
php artisan otp app-name
```

## Testing

Testing

You can run the tests using Pest:

```bash
composer test
composer test-coverage
```
## Changelog

Please see the [Latest Release](https://github.com/comes/simpleauthenticator/releases) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Jeremias Wolff](https://github.com/comes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
