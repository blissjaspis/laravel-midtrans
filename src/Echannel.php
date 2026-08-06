<?php

namespace BlissJaspis\Midtrans;

use BlissJaspis\Midtrans\Traits\Base;
use BlissJaspis\Midtrans\Traits\ChargesWithPaymentType;

class Echannel
{
    use Base, ChargesWithPaymentType;

    protected function paymentType(): string
    {
        return 'echannel';
    }
}
