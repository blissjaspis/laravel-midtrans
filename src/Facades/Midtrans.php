<?php

namespace BlissJaspis\Midtrans\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array cancelTransaction(string $transactionIdOrOrderId)
 * @method static array refundTransaction(string $transactionIdOrOrderId, array $params)
 * @method static array directRefundTransaction(string $transactionIdOrOrderId, array $params)
 * @method static array chargeTransaction(array $params)
 * @method static array captureTransaction(array $params)
 * @method static array expireTransaction(string $transactionIdOrOrderId)
 * @method static array getTransactionStatus(string $transactionIdOrOrderId)
 * @method static array getTransactionStatusB2B(string $transactionIdOrOrderId)
 * @method static array translateTransactionStatus(string $status)
 * @method static \BlissJaspis\Midtrans\CreditCard creditCard()
 * @method static \BlissJaspis\Midtrans\Gopay gopay()
 * 
 * @see \BlissJaspis\Midtrans\Midtrans
 */
final class Midtrans extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BlissJaspis\Midtrans\Midtrans::class;
    }
}