<?php

namespace BlissJaspis\Midtrans\Translator;

class FraudStatus
{
    const ACCEPT = 'accept';

    const DENY = 'deny';

    const CHALLENGE = 'challenge';

    public function translate(string $status): array
    {
        return match ($status) {
            self::ACCEPT => $this->accept(),
            self::DENY => $this->deny(),
            self::CHALLENGE => $this->challenge(),
            default => $this->respond('Invalid fraud status', 400),
        };
    }

    private function respond(string $message, int $code = 200): array
    {
        return [
            'code' => $code,
            'status' => $code === 200 ? 'success' : 'error',
            'message' => $message,
        ];
    }

    private function accept(): array
    {
        return $this->respond('Transaction is safe to proceed. Its not considered as fraud.');
    }

    private function deny(): array
    {
        return $this->respond('Transaction is considered as fraud. It is denied by Midtrans.');
    }

    private function challenge(): array
    {
        return $this->respond('Transaction is flagged as challenge by Midtrans Fraud Detection System. Approve or deny the transaction.');
    }
}
