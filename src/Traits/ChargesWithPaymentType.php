<?php

namespace BlissJaspis\Midtrans\Traits;

trait ChargesWithPaymentType
{
    abstract protected function paymentType(): string;

    public function charge(array $params)
    {
        $params['payment_type'] = $this->paymentType();

        return $this->chargeTransaction($params);
    }
}
