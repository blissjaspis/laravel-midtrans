<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Traits\Base;
use BlissJaspis\Midtrans\Traits\HasSubscription;

class Gopay
{
    use Base, HasSubscription;

    public function createPayAccount(array $params)
    {
        return HttpRequest::sendRequest('POST', '/pay/account', [
            'payment_type' => 'gopay',
            'gopay_partner' => [
                'phone_number' => $params['phone_number'],
                'country_code' => $params['country_code'],
                'redirect_url' => $params['redirect_url'],
            ],
        ]);
    }

    public function getAccountLinkedStatus(string $accountId)
    {
        return HttpRequest::sendRequest('GET', '/pay/account/' . $accountId);
    }

    public function unbindAccount(string $accountId)
    {
        return HttpRequest::sendRequest('POST', '/pay/account/' . $accountId . '/unbind');
    }
}