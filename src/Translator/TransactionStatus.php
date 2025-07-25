<?php

namespace BlissJaspis\Midtrans\Translator;

class TransactionStatus
{
    public const AUTHORIZED = 'authorized';
    public const CAPTURE = 'capture';
    public const SETTLEMENT = 'settlement';
    public const DENY = 'deny';
    public const PENDING = 'pending';
    public const CANCEL = 'cancel';
    public const REFUND = 'refund';
    public const PARTIAL_REFUND = 'partial_refund';
    public const CHARGEBACK = 'chargeback';
    public const PARTIAL_CHARGEBACK = 'partial_chargeback';
    public const EXPIRE = 'expire';
    public const FAILURE = 'failure';

    public function translate(string $status)
    {
        return match ($status) {
            self::AUTHORIZED => $this->authorized(),
            self::CAPTURE => $this->capture(),
            self::SETTLEMENT => $this->settlement(),
            self::DENY => $this->deny(),
            self::PENDING => $this->pending(),
            self::CANCEL => $this->cancel(),
            self::REFUND => $this->refund(),
            self::PARTIAL_REFUND => $this->partialRefund(),
            self::CHARGEBACK => $this->chargeback(),
            self::PARTIAL_CHARGEBACK => $this->partialChargeback(),
            self::EXPIRE => $this->expire(),
            self::FAILURE => $this->failure(),
            default => $this->respond('Unknown status'),
        };
    }

    private function respond(string $message)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
    
    private function authorized()
    {
        return $this->respond('Midtrans authorizes the payment card used for the transaction. Must be captured to process the balance.
Note: This is valid for payment card only.');
    }

    private function capture()
    {
        return $this->respond('The transaction is successful and the card balance is captured successfully. If no action is taken by you, the transaction will be successfully settled within 24 hours or within the agreed settlement time with your partner bank. The transaction status is settlement. It is safe to assume a successful payment.');
    }

    private function settlement()
    {
        return $this->respond('The transaction is successfully settled. Funds have been credited to your account.');
    }

    private function deny()
    {
        return $this->respond('The credentials used for payment are rejected by the payment provider or Midtrans Fraud Detection System (FDS). To know the reason and details for the denied transaction, see the status_message in the response.');
    }

    private function pending()
    {
        return $this->respond('The transaction is created and is waiting to be paid by the customer at the payment providers like direct debit, Bank Transfer, E-wallet, and so on.');
    }   

    private function cancel()
    {
        return $this->respond('The transaction is cancelled. This can be triggered by Midtrans or the partner bank. Note: For card payments, cancel status is triggered by Midtrans in case of Pre-Authorization transaction when an Authorized transaction exceeded the capture time limit');
    }

    private function refund()
    {
        return $this->respond('The transaction is marked to be refunded. Refund status is triggered by you.');
    }

    private function partialRefund()
    {
        return $this->respond('The transaction is marked to be partially refunded.');
    }

    private function chargeback()
    {
        return $this->respond('The transaction is marked to be charged back. Note: Applicable for payment card only.');
    }

    private function partialChargeback()
    {
        return $this->respond('The transaction is marked to be partially charged back.');
    }

    private function expire()
    {
        return $this->respond('The transaction is not available for processing, because the payment was delayed.');
    }

    private function failure()
    {
        return $this->respond('Unexpected error occurred during transaction processing.');
    }
    
}