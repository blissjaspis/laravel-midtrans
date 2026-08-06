<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Supports\HttpRequest;
use BlissJaspis\Midtrans\Traits\Base;
use BlissJaspis\Midtrans\Translator\FraudStatus;
use BlissJaspis\Midtrans\Translator\TransactionStatus;

class Midtrans
{
    use Base;

    public function captureTransaction(array $params)
    {
        return HttpRequest::sendRequest(
            'POST',
            '/capture',
            array_filter([
                'transaction_id' => $params['transaction_id'],
                'gross_amount' => $params['gross_amount'] ?? null,
            ], fn ($value) => $value !== null)
        );
    }

    public function approveTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/'.$transactionIdOrOrderId.'/approve');
    }

    public function denyTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/'.$transactionIdOrOrderId.'/deny');
    }

    public function expireTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/'.$transactionIdOrOrderId.'/expire');
    }

    public function getTransactionStatus(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('GET', '/'.$transactionIdOrOrderId.'/status');
    }

    public function getTransactionStatusB2B(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('GET', '/'.$transactionIdOrOrderId.'/status/b2b');
    }

    public function isValidNotificationSignature(array $payload, ?string $serverKey = null): bool
    {
        $signatureKey = $payload['signature_key'] ?? null;

        if (! is_string($signatureKey) || $signatureKey === '') {
            return false;
        }

        $expected = hash(
            'sha512',
            ($payload['order_id'] ?? '').
            ($payload['status_code'] ?? '').
            ($payload['gross_amount'] ?? '').
            ($serverKey ?? config('midtrans.server_key'))
        );

        return hash_equals($expected, $signatureKey);
    }

    public function translateTransactionStatus(string $status)
    {
        return (new TransactionStatus)->translate($status);
    }

    public function translateFraudStatus(string $status)
    {
        return (new FraudStatus)->translate($status);
    }

    public function creditCard()
    {
        return new CreditCard;
    }

    public function gopay()
    {
        return new Gopay;
    }

    public function bankTransfer()
    {
        return new BankTransfer;
    }

    public function echannel()
    {
        return new Echannel;
    }

    public function shopeePay()
    {
        return new ShopeePay;
    }

    public function qris()
    {
        return new Qris;
    }

    public function akulaku()
    {
        return new Akulaku;
    }

    public function kredivo()
    {
        return new Kredivo;
    }

    public function convenienceStore()
    {
        return new ConvenienceStore;
    }
}
