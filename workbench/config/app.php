<?php

return [
    'name' => 'RajaOngkir',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'Asia/Jakarta',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => env('APP_KEY', 'base64:'.base64_encode(random_bytes(32))),
    'cipher' => 'AES-256-CBC',
    'providers' => [
        BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider::class,
    ]
];