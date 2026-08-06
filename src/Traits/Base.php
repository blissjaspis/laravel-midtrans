<?php

namespace BlissJaspis\Midtrans\Traits;

use BlissJaspis\Midtrans\Supports\HttpRequest;

trait Base
{
    public function chargeTransaction(array $params)
    {
        return HttpRequest::sendRequest('POST', '/charge', $params);
    }

    /**
     * Cancel a pending or authorized transaction.
     * If the transaction is already settled, use refundTransaction instead.
     */
    public function cancelTransaction(string $transactionIdOrOrderId)
    {
        return HttpRequest::sendRequest('POST', '/'.$transactionIdOrOrderId.'/cancel');
    }

    /**
     * Refund a settled transaction.
     * If the transaction is not settled, use cancelTransaction instead.
     *
     * Refund transaction is supported only for credit_card, gopay, shopeepay,
     * QRIS, kredivo and akulaku payment methods.
     */
    public function refundTransaction(string $transactionIdOrOrderId, array $params = [])
    {
        return HttpRequest::sendRequest(
            'POST',
            '/'.$transactionIdOrOrderId.'/refund',
            array_filter([
                'refund_key' => $params['refund_key'] ?? null,
                'amount' => $params['amount'] ?? null,
                'reason' => $params['reason'] ?? null,
            ], fn ($value) => $value !== null)
        );
    }

    public function directRefundTransaction(string $transactionIdOrOrderId, array $params = [])
    {
        return HttpRequest::sendRequest(
            'POST',
            '/'.$transactionIdOrOrderId.'/refund/online/direct',
            array_filter([
                'refund_key' => $params['refund_key'] ?? null,
                'amount' => $params['amount'] ?? null,
                'reason' => $params['reason'] ?? null,
            ], fn ($value) => $value !== null)
        );
    }
}
