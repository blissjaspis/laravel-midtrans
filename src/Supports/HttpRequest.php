<?php

namespace BlissJaspis\Midtrans\Supports;

use BlissJaspis\Midtrans\Exceptions\InvalidConfigurationException;
use BlissJaspis\Midtrans\Exceptions\MidtransApiException;
use Illuminate\Http\Client\RequestException;
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
        $this->serverKey = (string) config('midtrans.server_key', '');
        $this->baseUrl = $this->linkEnv[config('midtrans.is_production') ? 'production' : 'sandbox'];

        if ($this->serverKey === '') {
            throw new InvalidConfigurationException(
                'Midtrans server key is not configured. Set MIDTRANS_SERVER_KEY in your environment.'
            );
        }
    }

    private function make(string $method, string $path, array $data = [], string $version = 'v2')
    {
        $timeout = (int) config('midtrans.timeout', 10);
        $connectTimeout = (int) config('midtrans.connect_timeout', 10);

        $request = Http::baseUrl($this->baseUrl.'/'.$version)
            ->withToken(base64_encode($this->serverKey.':'), 'Basic')
            ->withHeaders($this->headers)
            ->timeout($timeout)
            ->connectTimeout($connectTimeout);

        try {
            $response = match (strtoupper($method)) {
                'GET' => $request->get($path, $data),
                'PUT' => $request->put($path, $data),
                'PATCH' => $request->patch($path, $data),
                'DELETE' => $request->delete($path, $data),
                default => $request->post($path, $data),
            };

            return $response->throw()->json();
        } catch (RequestException $exception) {
            throw MidtransApiException::fromResponse($exception->response, $exception);
        }
    }

    public static function sendRequest(string $method, string $path, array $data = [], string $version = 'v2')
    {
        $instance = new static;

        return $instance->make($method, $path, $data, $version);
    }
}
