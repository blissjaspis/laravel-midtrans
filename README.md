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
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
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
        $token = Midtrans::creditCard()->getToken([
            'card_number' => '4355084355084358',
            'card_exp_month' => '07',
            'card_exp_year' => '2025',
            'card_cvv' => '123',
            'client_key' => config('midtrans.client_key'),
            'token_id' => '1234567890',
        ]);

        return $token;
    }

    public function chargeQRIS()
    {
        $charge = Midtrans::chargeTransaction([
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => '1234567890',
                'gross_amount' => 10000,
            ],
            'item_details' => [
                [
                    'id' => '1234567890',
                    'price' => 10000,
                    'quantity' => 1,
                ]
            ],
            'customer_details' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '081234567890',
            ],
            'qris': [
                'acquirer' => 'gopay'
            ]
        ]);
    }

    public function refundTransaction()
    {
        $refund = Midtrans::gopay()->refundTransaction('1234567890', [
            'amount' => 10000,
            'refund_key' => '1234567890',
            'reason' => 'test',
        ]);
    }

    public function translateTransactionStatus()
    {
        $status = Midtrans::translateTransactionStatus('capture');
        return $status;
    }
}
```

### Available Methods

#### Midtrans

- `chargeTransaction(array $params)`
- `captureTransaction(array $params)`
- `expireTransaction(string $transactionIdOrOrderId)`
- `getTransactionStatus(string $transactionIdOrOrderId)`
- `getTransactionStatusB2B(string $transactionIdOrOrderId)`
- `translateTransactionStatus(string $status)`
- `creditCard()`
- `gopay()`

#### Credit Card
- `getToken(array $params)`
- `registerCard(array $params)`
- `getPointInquiry(string $cardToken)`
- `getBankIdentificationNumber(string $binNumber)`
- `cancelTransaction(string $transactionIdOrOrderId)`
- `refundTransaction(string $transactionIdOrOrderId, array $params)`
- `directRefundTransaction(string $transactionIdOrOrderId, array $params)`
- `createSubscription(array $params)`
- `getSubscription(string $subscriptionId)`
- `disableSubscription(string $subscriptionId)`
- `enableSubscription(string $subscriptionId)`
- `updateSubscription(string $subscriptionId, array $params)`

#### Gopay
- `createPayAccount(array $params)`
- `getAccountLinkedStatus(string $accountId)`
- `unbindAccount(string $accountId)`
- `cancelTransaction(string $transactionIdOrOrderId)`
- `refundTransaction(string $transactionIdOrOrderId, array $params)`
- `directRefundTransaction(string $transactionIdOrOrderId, array $params)`
- `createSubscription(array $params)`
- `getSubscription(string $subscriptionId)`
- `disableSubscription(string $subscriptionId)`
- `enableSubscription(string $subscriptionId)`
- `updateSubscription(string $subscriptionId, array $params)`


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