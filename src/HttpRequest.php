<?php

namespace BlissJaspis\Midtrans;

use Illuminate\Support\Facades\Http;

class HttpRequest
{
    protected $baseUrl;
    protected $serverKey;
    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    protected $linkEnv = [
        'sandbox' => 'https://api.sandbox.midtrans.com',
        'production' => 'https://api.midtrans.com',
    ];

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->baseUrl = $this->linkEnv[config('midtrans.is_production') ? 'production' : 'sandbox'];
    }

    private function make(string $method, string $path, array $data = [], string $version = 'v2')
    {
        $request = Http::baseUrl($this->baseUrl . '/' . $version)
            ->withBasicAuth($this->serverKey, '')
            ->withHeaders($this->headers)
            ->timeout(10)
            ->connectTimeout(10)
            ->withToken(base64_encode($this->serverKey . ':'), 'Basic');


        $response = match (strtoupper($method)) {
            'GET' => $request->get($path, $data),
            default => $request->post($path, $data),
        };

        return $response->throw()->json();
    }

    public static function sendRequest(string $method, string $path, array $data = [], string $version = 'v2')
    {
        $instance = new static();
        
        return $instance->make($method, $path, $data, $version);
    }
}