<?php

namespace App\Service\Payment;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface PaymentInterface
{
    public function __construct(UrlGeneratorInterface $urlGenerator);

    /**
     * @param int $paymentTotal
     * @param string $actionUrl
     * @return void
     */
    public function processPayment(int $paymentTotal): void;
}