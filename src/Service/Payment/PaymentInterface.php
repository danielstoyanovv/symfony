<?php

namespace App\Service\Payment;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface PaymentInterface
{
    public function __construct(UrlGeneratorInterface $urlGenerator);

    /**
     * @param int $paymentTotal
     * @return void
     */
    public function processPayment(int $paymentTotal): void;
}