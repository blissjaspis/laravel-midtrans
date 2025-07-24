<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Traits\Base;
use BlissJaspis\Midtrans\HttpRequest;

class CreditCard
{
    use Base;
    
    public function getPointInquiry(string $accountId)
    {
        return $this->httpRequest->sendRequest('GET', '/point/inquiry' . $accountId);
    }

    public function registerCard(array $params)
    {
        return HttpRequest::sendRequest('POST', '/card/register', [
            'card_number' => $params['card_number'],
            'card_exp_month' => $params['card_exp_month'],
            'card_exp_year' => $params['card_exp_year'],
            'client_key' => config('midtrans.client_key'),
        ]);
    }

    public function getBankIdentificationNumber(string $bin)
    {
        return HttpRequest::sendRequest('GET', '/bins/' . $bin, [], 'v1');
    }
}
