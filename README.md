# Laravel Midtrans

> **Note**
> This package supports Laravel versions 10, 11, and 12.

This package provides a simple and easy-to-use Laravel wrapper for the Midtrans API.

## Installation

You can install the package via composer:

```bash
composer require bliss-jaspis/laravel-midtrans
```

You must publish the configuration file with:

```bash
php artisan vendor:publish --provider="BlissJaspis\Midtrans\Providers\MidtransServiceProvider" --tag="config"
```

This will create a `config/midtrans.php` file in your `config` directory.

Add the following to your `.env` file:

```env
MIDTRANS_SERVER_KEY=your-api-key
MIDTRANS_BASE_URL=https://api.sandbox.midtrans.com
```

## Usage

You can use this package by injecting the `BlissJaspis\Midtrans\Midtrans` class into your controller or service.

```php
use BlissJaspis\Midtrans\Facades\Midtrans;

class YourController
{
    // ...

    public function getToken()
    {
        $token = Midtrans::getToken();

        return $token;
    }
}
```

### Available Methods

- `getToken(array $params)`
- `chargeTransaction(array $params)`
- `captureTransaction(array $params)`
- `expireTransaction(string $transactionIdOrOrderId)`
- `getTransactionStatus(string $transactionIdOrOrderId)`
- `getTransactionStatusB2B(string $transactionIdOrOrderId)`
- `creditCard()`
- `gopay()`

### **API Reference**
> For more detailed information about the API endpoints, parameters, and response structures, please refer to the official [Midtrans API Documentation](https://midtrans.com/en/documentation).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bliss Jaspis](https://github.com/blissjaspis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.