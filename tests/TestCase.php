<?php

namespace BlissJaspis\Midtrans\Tests;

use BlissJaspis\Midtrans\Providers\MidtransServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;
    
    protected function setUp(): void
    {
        parent::setUp();
        // additional setup if needed
    }

    protected function getPackageProviders($app): array
    {
        return [
            MidtransServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        tap($app['config'], function ($config) {
            $config->set('database.default', 'sqlite');
            $config->set('database.connections.sqlite', [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ]);
            $config->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
            $config->set('midtrans.server_key', 'your-api-key');
            $config->set('midtrans.base_url', 'https://api.sandbox.midtrans.com');
        });
    }
}