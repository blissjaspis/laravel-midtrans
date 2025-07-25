<?php

namespace BlissJaspis\Midtrans\Traits;

use BlissJaspis\Midtrans\Supports\HttpRequest;

trait Base
{
    /**
     * Use this method to expire a transaction.
     * if the transaction is already settled, use refundTransaction instead.
     */
    public function cancelTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/' . $transactionIdOrOrderId . '/cancel');
    }
    
    /**
     * Use this method to refund a transaction.
     * if the transaction is not settled, use cancelTransaction instead.
     *
     * Refund transaction is supported only for credit_card, gopay, shopeepay,
     * QRIS, kredivo and akulaku payment methods.
     */
    public function refundTransaction(string $transactionIdOrOrderId, array $params)
    {
        return HttpRequest::sendRequest('POST', '/' . $transactionIdOrOrderId . '/refund', [
            'refund_key' => $params['refund_key'],
            'amount' => $params['amount'],
            'reason' => $params['reason'],
        ]);
    }

    public function directRefundTransaction(string $transactionIdOrOrderId, array $params)
    {
        return HttpRequest::sendRequest('POST', '/' . $transactionIdOrOrderId . '/refund/online/direct', [
            'refund_key' => $params['refund_key'],
            'amount' => $params['amount'],
            'reason' => $params['reason'],
        ]);
    }
}