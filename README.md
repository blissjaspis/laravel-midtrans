# Laravel Midtrans

> **Note**
> This package supports Laravel versions 11, 12, and 13.

This package provides a simple and easy-to-use Laravel wrapper for the Midtrans API.

## Installation

You can install the package via composer:

```bash
composer require blissjaspis/laravel-midtrans
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

You can use facade `Midtrans` to use this package.

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
            'qris' => [
                'acquirer' => 'gopay'
            ]
        ]);
    }

    public function handleNotification()
    {
        $payload = request()->all();

        if (! Midtrans::isValidNotificationSignature($payload)) {
            abort(403, 'Invalid Midtrans signature.');
        }

        // Handle notification...
    }

    public function refundTransaction()
    {
        Midtrans::gopay()->refundTransaction('order-id-123', [
            'amount' => 50000,
            'refund_key' => '1234567890',
            'reason' => 'Item out of stock',
        ]);

        // Or you can use the trait Base to refund transaction
        // This method is recommended to use if you want to refund transaction without knowing the payment type
        Midtrans::refundTransaction('order-id-123', [
            'refund_key' => 'my-refund-key',
            'amount' => 50000,
            'reason' => 'Item out of stock',
        ]);
    }

    public function cancelTransaction()
    {
        Midtrans::creditCard()->cancelTransaction('order-id-123');

        // Or you can use the trait Base to cancel transaction
        // This method is recommended to use if you want to cancel transaction without knowing the payment type
        Midtrans::cancelTransaction('order-id-456');
    }

    public function translateTransactionStatus()
    {
        $status = Midtrans::translateTransactionStatus('capture');
        return $status;
    }

    public function translateFraudStatus()
    {
        $status = Midtrans::translateFraudStatus('accept');
        return $status;
    }
}
```

### Available Methods

#### Midtrans

- `cancelTransaction(string $transactionIdOrOrderId)`
- `refundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `directRefundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `chargeTransaction(array $params)`
- `captureTransaction(array $params)`
- `approveTransaction(string $transactionIdOrOrderId)`
- `denyTransaction(string $transactionIdOrOrderId)`
- `expireTransaction(string $transactionIdOrOrderId)`
- `getTransactionStatus(string $transactionIdOrOrderId)`
- `getTransactionStatusB2B(string $transactionIdOrOrderId)`
- `isValidNotificationSignature(array $payload, ?string $serverKey = null)`
- `translateTransactionStatus(string $status)`
- `translateFraudStatus(string $status)`
- `creditCard()`
- `gopay()`

#### Credit Card
- `chargeTransaction(array $params)`
- `getToken(array $params)`
- `registerCard(array $params)`
- `getPointInquiry(string $cardToken, ?string $grossAmount = null)`
- `getBankIdentificationNumber(string $binNumber)`
- `cancelTransaction(string $transactionIdOrOrderId)`
- `refundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `directRefundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `createSubscription(array $params)`
- `getSubscription(string $subscriptionId)`
- `disableSubscription(string $subscriptionId)`
- `cancelSubscription(string $subscriptionId)`
- `enableSubscription(string $subscriptionId)`
- `updateSubscription(string $subscriptionId, array $params)`

#### Gopay
- `chargeTransaction(array $params)`
- `createPayAccount(array $params)`
- `getAccountLinkedStatus(string $accountId)`
- `unbindAccount(string $accountId)`
- `cancelTransaction(string $transactionIdOrOrderId)`
- `refundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `directRefundTransaction(string $transactionIdOrOrderId, array $params = [])`
- `createSubscription(array $params)`
- `getSubscription(string $subscriptionId)`
- `disableSubscription(string $subscriptionId)`
- `cancelSubscription(string $subscriptionId)`
- `enableSubscription(string $subscriptionId)`
- `updateSubscription(string $subscriptionId, array $params)`


### **API Reference**
> For more detailed information about the API endpoints, parameters, and response structures, please refer to the official [Midtrans API Documentation](https://docs.midtrans.com).

## Testing

```bash
composer test
```

This package uses [Pest](https://pestphp.com) for testing. Running tests requires PHP 8.3+.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bliss Jaspis](https://github.com/blissjaspis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
