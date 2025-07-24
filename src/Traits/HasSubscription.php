<?php

namespace BlissJaspis\Midtrans\Traits;

use BlissJaspis\Midtrans\HttpRequest;

trait HasSubscription
{
    public function createSubscription(array $params)
    {
        return HttpRequest::sendRequest('POST', '/subscriptions', $params, 'v1');
    }

    public function getSubscription(string $subscriptionId)
    {
        return HttpRequest::sendRequest('GET', '/subscriptions/' . $subscriptionId, [], 'v1');
    }

    public function disableSubscription(string $subscriptionId)
    {
        return HttpRequest::sendRequest('POST', '/subscriptions/' . $subscriptionId . '/disable', [], 'v1');
    }

    public function cancelSubscription(string $subscriptionId)  
    {
        return HttpRequest::sendRequest('POST', '/subscriptions/' . $subscriptionId . '/cancel', [], 'v1');
    }

    public function enableSubscription(string $subscriptionId)
    {
        return HttpRequest::sendRequest('POST', '/subscriptions/' . $subscriptionId . '/enable', [], 'v1');
    }

    public function updateSubscription(string $subscriptionId, array $params)
    {
        return HttpRequest::sendRequest('PUT', '/subscriptions/' . $subscriptionId, $params, 'v1');
    }

}