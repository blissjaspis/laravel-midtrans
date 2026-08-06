<?php

use BlissJaspis\Midtrans\Exceptions\InvalidConfigurationException;
use BlissJaspis\Midtrans\Exceptions\MidtransApiException;
use BlissJaspis\Midtrans\Facades\Midtrans;
use BlissJaspis\Midtrans\Midtrans as MidtransClient;
use Illuminate\Support\Facades\Http;

it('can be instantiated', function () {
    expect(new MidtransClient)->toBeInstanceOf(MidtransClient::class);
});

it('can publish the config file', function () {
    $this->artisan('vendor:publish --provider="BlissJaspis\Midtrans\Providers\MidtransServiceProvider"')
        ->assertExitCode(0);
});

it('registers a card with GET and query parameters', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/card/register*' => Http::response([
            'status_code' => '200',
            'saved_token_id' => 'token-123',
        ], 200),
    ]);

    Midtrans::creditCard()->registerCard([
        'card_number' => '4811111111111114',
        'card_exp_month' => '12',
        'card_exp_year' => '2028',
    ]);

    Http::assertSent(fn ($request) => $request->method() === 'GET'
        && str_contains($request->url(), '/v2/card/register')
        && $request['card_number'] === '4811111111111114'
        && $request['client_key'] === 'SB-Mid-client-test');
});

it('queries point inquiry with the correct path', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/point_inquiry/*' => Http::response([
            'status_code' => '200',
            'point_balance_amount' => '10000.00',
        ], 200),
    ]);

    Midtrans::creditCard()->getPointInquiry('token-abc', '10000');

    Http::assertSent(fn ($request) => $request->method() === 'GET'
        && str_contains($request->url(), '/v2/point_inquiry/token-abc')
        && $request['gross_amount'] === '10000');
});

it('updates a subscription with PATCH', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v1/subscriptions/*' => Http::response([
            'id' => 'sub-1',
            'status' => 'active',
        ], 200),
    ]);

    Midtrans::creditCard()->updateSubscription('sub-1', [
        'name' => 'Monthly Plan',
        'amount' => '10000',
        'currency' => 'IDR',
        'token' => 'token-abc',
        'schedule' => ['interval' => '1'],
    ]);

    Http::assertSent(fn ($request) => $request->method() === 'PATCH'
        && str_contains($request->url(), '/v1/subscriptions/sub-1'));
});

it('approves and denies transactions on the expected endpoints', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/*/approve' => Http::response(['status_code' => '200'], 200),
        'https://api.sandbox.midtrans.com/v2/*/deny' => Http::response(['status_code' => '200'], 200),
    ]);

    Midtrans::approveTransaction('order-1');
    Midtrans::denyTransaction('order-2');

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && str_ends_with(parse_url($request->url(), PHP_URL_PATH), '/order-1/approve'));
    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && str_ends_with(parse_url($request->url(), PHP_URL_PATH), '/order-2/deny'));
});

it('omits optional null fields when refunding', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/*/refund' => Http::response(['status_code' => '200'], 200),
    ]);

    Midtrans::refundTransaction('order-1', [
        'amount' => 50000,
    ]);

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->method() === 'POST'
            && str_ends_with(parse_url($request->url(), PHP_URL_PATH), '/order-1/refund')
            && $data['amount'] === 50000
            && ! array_key_exists('refund_key', $data)
            && ! array_key_exists('reason', $data);
    });
});

it('validates notification signatures', function () {
    $payload = [
        'order_id' => 'order-1',
        'status_code' => '200',
        'gross_amount' => '10000.00',
        'signature_key' => hash('sha512', 'order-120010000.00SB-Mid-server-test'),
    ];

    expect(Midtrans::isValidNotificationSignature($payload))->toBeTrue()
        ->and(Midtrans::isValidNotificationSignature([
            ...$payload,
            'signature_key' => 'invalid',
        ]))->toBeFalse();
});

it('translates fraud status for deny and challenge', function () {
    $deny = Midtrans::translateFraudStatus('deny');
    $challenge = Midtrans::translateFraudStatus('challenge');
    $invalid = Midtrans::translateFraudStatus('reject');

    expect($deny)->toBeArray()
        ->and($deny['code'])->toBe(200)
        ->and($challenge['code'])->toBe(200)
        ->and($invalid['code'])->toBe(400);
});

it('returns an array for transaction status translations', function () {
    $status = Midtrans::translateTransactionStatus('settlement');

    expect($status)->toBeArray()
        ->and($status['code'])->toBe(200)
        ->and($status['status'])->toBe('success');
});

it('charges bank transfer with payment_type set automatically', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/charge' => Http::response(['status_code' => '201'], 201),
    ]);

    Midtrans::bankTransfer()->charge([
        'transaction_details' => [
            'order_id' => 'order-va-1',
            'gross_amount' => 10000,
        ],
        'bank_transfer' => [
            'bank' => 'bca',
        ],
    ]);

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->method() === 'POST'
            && str_ends_with(parse_url($request->url(), PHP_URL_PATH), '/charge')
            && $data['payment_type'] === 'bank_transfer'
            && $data['bank_transfer']['bank'] === 'bca';
    });
});

it('charges qris shopeepay akulaku and convenience store helpers', function (string $method, string $paymentType) {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/charge' => Http::response(['status_code' => '201'], 201),
    ]);

    Midtrans::{$method}()->charge([
        'transaction_details' => [
            'order_id' => 'order-'.$paymentType,
            'gross_amount' => 15000,
        ],
    ]);

    Http::assertSent(fn ($request) => $request->data()['payment_type'] === $paymentType);
})->with([
    ['qris', 'qris'],
    ['shopeePay', 'shopeepay'],
    ['akulaku', 'akulaku'],
    ['kredivo', 'kredivo'],
    ['echannel', 'echannel'],
    ['convenienceStore', 'cstore'],
    ['gopay', 'gopay'],
]);

it('throws when the server key is missing', function () {
    config()->set('midtrans.server_key', '');

    Midtrans::getTransactionStatus('order-1');
})->throws(InvalidConfigurationException::class);

it('throws a MidtransApiException with validation details', function () {
    Http::fake([
        'https://api.sandbox.midtrans.com/v2/charge' => Http::response([
            'status_code' => '400',
            'status_message' => 'One or more parameters in the payload is invalid.',
            'validation_messages' => [
                'transaction_details.order_id is required',
            ],
        ], 400),
    ]);

    try {
        Midtrans::qris()->charge([
            'transaction_details' => [
                'gross_amount' => 10000,
            ],
        ]);
        $this->fail('Expected MidtransApiException was not thrown.');
    } catch (MidtransApiException $exception) {
        expect($exception->statusCode())->toBe('400')
            ->and($exception->getMessage())->toContain('parameters')
            ->and($exception->validationMessages())->toContain('transaction_details.order_id is required')
            ->and($exception->httpStatus())->toBe(400)
            ->and($exception->responseBody()['status_code'])->toBe('400');
    }
});
