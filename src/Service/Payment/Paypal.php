<?php

namespace App\Service\Payment;

use App\Checkout\PaypalForm;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Paypal implements PaymentInterface
{
    use PaypalForm;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param int $paymentTotal
     * @return void
     */
    public function processPayment(int $paymentTotal): void
    {
        $this->getPaypalForm($paymentTotal, $this->urlGenerator->generate('paypal_pay', [], 0));
    }
}