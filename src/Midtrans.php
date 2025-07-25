<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Translator\TransactionStatus;

class Midtrans
{
    public function chargeTransaction(array $params)
    {
        return HttpRequest::sendRequest('POST', '/charge', $params);
    }

    public function captureTransaction(array $params)
    {
        return HttpRequest::sendRequest('POST', '/capture', [
            'transaction_id' => $params['transaction_id'],
            'gross_amount' => $params['gross_amount'],
        ]);
    }

    public function expireTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/' . $transactionIdOrOrderId . '/expire');
    }

    public function getTransactionStatus(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('GET', '/' . $transactionIdOrOrderId . '/status');
    }

    public function getTransactionStatusB2B(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('GET', '/' . $transactionIdOrOrderId . '/status/b2b');
    }

    public function translateTransactionStatus(string $status)
    {
        return (new TransactionStatus())->translate($status);
    }
    
    public function creditCard()
    {
        return new CreditCard();
    }

    public function gopay()
    {
        return new Gopay();
    }
}