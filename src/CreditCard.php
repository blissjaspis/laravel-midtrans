<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Supports\HttpRequest;
use BlissJaspis\Midtrans\Traits\Base;
use BlissJaspis\Midtrans\Traits\HasSubscription;

class CreditCard
{
    use Base, HasSubscription;

    public function getToken(array $params)
    {
        return HttpRequest::sendRequest('GET', '/token', $params);
    }

    public function getPointInquiry(string $tokenId, ?string $grossAmount = null)
    {
        $query = array_filter([
            'gross_amount' => $grossAmount,
        ], fn ($value) => $value !== null);

        return HttpRequest::sendRequest('GET', '/point_inquiry/'.$tokenId, $query);
    }

    public function registerCard(array $params)
    {
        return HttpRequest::sendRequest('GET', '/card/register', [
            'card_number' => $params['card_number'],
            'card_exp_month' => $params['card_exp_month'],
            'card_exp_year' => $params['card_exp_year'],
            'client_key' => $params['client_key'] ?? config('midtrans.client_key'),
        ]);
    }

    public function getBankIdentificationNumber(string $bin)
    {
        return HttpRequest::sendRequest('GET', '/bins/'.$bin, [], 'v1');
    }
}
