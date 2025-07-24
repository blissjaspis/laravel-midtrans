<?php

namespace BlissJaspis\Midtrans\Tests\Unit;

use BlissJaspis\Midtrans\Midtrans;
use BlissJaspis\Midtrans\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LaravelMidtransTest extends TestCase
{
    #[Test]
    public function laravel_midtrans_can_be_instantiated()
    {
        $midtrans = new Midtrans();
        $this->assertInstanceOf(Midtrans::class, $midtrans);
    }

    #[Test]
    public function config_can_publish()
    {
        $this->artisan('vendor:publish --provider="BlissJaspis\Midtrans\Providers\MidtransServiceProvider"')
            ->assertExitCode(0);
    }
}