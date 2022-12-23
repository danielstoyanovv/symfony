<?php

namespace App\Service\Payment;

interface PaymentInterface
{
    /**
     * @param int $paymentTotal
     * @param string $actionUrl
     * @return void
     */
    public function processPayment(int $paymentTotal, string $actionUrl): void;
}