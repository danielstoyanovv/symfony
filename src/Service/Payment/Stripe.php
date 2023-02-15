<?php

namespace App\Service\Payment;

use App\Checkout\Form;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Stripe implements PaymentInterface
{
    use Form;

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
        $this->getForm($paymentTotal, $this->urlGenerator->generate('stripe_pay', [], 0));
    }
}