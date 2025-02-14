<?php

namespace App\Services;

interface PaymentProcessorInterface
{
    /**
     * Process a payment.
     *
     * @param float $amount
     * @param array $paymentDetails (for future integrations)
     * @return bool
     */
    public function process(float $amount, array $paymentDetails = []): bool;
}
