<?php

namespace BlissJaspis\Midtrans\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Throwable;

class MidtransApiException extends Exception
{
    public function __construct(
        string $message,
        protected readonly ?string $statusCode = null,
        protected readonly array $validationMessages = [],
        protected readonly array $responseBody = [],
        protected readonly ?int $httpStatus = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $httpStatus ?? 0, $previous);
    }

    public static function fromResponse(Response $response, ?Throwable $previous = null): self
    {
        $body = $response->json() ?? [];

        if (! is_array($body)) {
            $body = [];
        }

        $statusCode = isset($body['status_code']) ? (string) $body['status_code'] : null;
        $message = (string) ($body['status_message'] ?? $body['error_message'] ?? $response->body() ?: 'Unexpected Midtrans API error.');
        $validationMessages = $body['validation_messages'] ?? [];

        if (! is_array($validationMessages)) {
            $validationMessages = [$validationMessages];
        }

        return new self(
            message: $message,
            statusCode: $statusCode,
            validationMessages: $validationMessages,
            responseBody: $body,
            httpStatus: $response->status(),
            previous: $previous,
        );
    }

    public function statusCode(): ?string
    {
        return $this->statusCode;
    }

    public function validationMessages(): array
    {
        return $this->validationMessages;
    }

    public function responseBody(): array
    {
        return $this->responseBody;
    }

    public function httpStatus(): ?int
    {
        return $this->httpStatus;
    }
}
