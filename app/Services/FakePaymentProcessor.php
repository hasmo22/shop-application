<?php

namespace App\Services;

class FakePaymentProcessor implements PaymentProcessorInterface
{
    /**
     * Process the payment for a given amount.
     * 
     * @param float $amount
     * @param array $paymentDetails
     * @return bool
     */
    public function process(float $amount, array $paymentDetails = []): bool
    {
        // simulate a successful payment...
        return true;
    }
}
