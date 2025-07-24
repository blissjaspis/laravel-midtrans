<?php

namespace BlissJaspis\Midtrans\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSnapToken(array $params)
 * @method static array getSnapRedirectUrl(array $params)
 * @method static array getTransactionStatus(string $transactionId)
 * @method static array getTransactionDetail(string $transactionId)
 * @method static array getTransactionList(array $params)
 * @method static array getTransactionNotification(string $transactionId)
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