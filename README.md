# Laravel Midtrans

> **Note**
> This package supports Laravel versions 11, 12, and 13.

This package provides a simple and easy-to-use Laravel wrapper for the Midtrans Core API.

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
MIDTRANS_TIMEOUT=10
MIDTRANS_CONNECT_TIMEOUT=10
```

## PCI guidance

For card payments, Midtrans recommends tokenizing sensitive card data **on the client** with Midtrans.js / the frontend `/v2/token` flow using your `client_key`. Avoid sending raw PAN/CVV through your Laravel backend when possible, so your server stays outside full PCI card-data scope.

Server-side `creditCard()->getToken()` / `registerCard()` helpers remain available for controlled backend flows, but prefer client-side tokenization in production apps.

## Usage

You can use facade `Midtrans` to use this package.

```php
use BlissJaspis\Midtrans\Exceptions\MidtransApiException;
use BlissJaspis\Midtrans\Facades\Midtrans;

class YourController
{
    // ...

    public function chargeBankTransfer()
    {
        try {
            return Midtrans::bankTransfer()->charge([
                'transaction_details' => [
                    'order_id' => 'order-123',
                    'gross_amount' => 10000,
                ],
                'bank_transfer' => [
                    'bank' => 'bca',
                ],
            ]);
        } catch (MidtransApiException $e) {
            // $e->statusCode(), $e->validationMessages(), $e->responseBody()
            report($e);
            abort(502, $e->getMessage());
        }
    }

    public function chargeQRIS()
    {
        return Midtrans::qris()->charge([
            'transaction_details' => [
                'order_id' => '1234567890',
                'gross_amount' => 10000,
            ],
            'item_details' => [
                [
                    'id' => '1234567890',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Product',
                ],
            ],
            'customer_details' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '081234567890',
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
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

        // Or refund without knowing the payment type:
        Midtrans::refundTransaction('order-id-123', [
            'refund_key' => 'my-refund-key',
            'amount' => 50000,
            'reason' => 'Item out of stock',
        ]);
    }

    public function cancelTransaction()
    {
        Midtrans::creditCard()->cancelTransaction('order-id-123');
        Midtrans::cancelTransaction('order-id-456');
    }

    public function translateTransactionStatus()
    {
        return Midtrans::translateTransactionStatus('capture');
    }

    public function translateFraudStatus()
    {
        return Midtrans::translateFraudStatus('accept');
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
- `bankTransfer()`
- `echannel()`
- `shopeePay()`
- `qris()`
- `akulaku()`
- `kredivo()`
- `convenienceStore()`

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
- `charge(array $params)` / `chargeTransaction(array $params)`
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

#### Other payment helpers
Each of these exposes `charge(array $params)` (sets `payment_type` for you) plus cancel/refund helpers from the shared base trait:

- `bankTransfer()` — Virtual Account (`bca`, `bni`, `bri`, `cimb`, `permata`)
- `echannel()` — Mandiri Bill Payment
- `shopeePay()`
- `qris()`
- `akulaku()`
- `kredivo()`
- `convenienceStore()` — Alfamart / Indomaret (`cstore`)

### Error handling

Failed Midtrans HTTP responses throw `BlissJaspis\Midtrans\Exceptions\MidtransApiException` with:

- `statusCode()` — Midtrans `status_code`
- `validationMessages()` — Midtrans `validation_messages`
- `responseBody()` — decoded response payload
- `httpStatus()` — HTTP status code

Missing `MIDTRANS_SERVER_KEY` throws `BlissJaspis\Midtrans\Exceptions\InvalidConfigurationException` before any request is sent.

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
