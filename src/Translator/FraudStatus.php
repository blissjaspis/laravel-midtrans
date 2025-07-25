<?php

namespace BlissJaspis\Midtrans\Translator;

class FraudStatus
{
    const ACCEPT = 'accept';
    const REJECT = 'reject';
    
    public function translate(string $status)
    {
        return match ($status) {
            self::ACCEPT => $this->accept(),
            self::REJECT => $this->reject(),
            default => $this->respond('Invalid fraud status', 400),
        };
    }

    public function respond(string $message, int $code = 200)
    {
        return response()->json([
            'code' => $code,
            'status' => $code === 200 ? 'success' : 'error',
            'message' => $message,
        ], $code);
    }

    private function accept()
    {
        return $this->respond('Transaction is safe to proceed. its not considered as fraud.');
    }

    private function reject()
    {
        return $this->respond('Transaction is considered as fraud. it is rejected by Midtrans.');
    }
}