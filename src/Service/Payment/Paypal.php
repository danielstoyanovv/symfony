<?php

namespace App\Service\Payment;

use App\Checkout\PaypalForm;

class Paypal implements PaymentInterface
{
    use PaypalForm;

    /**
     * @param int $paymentTotal
     * @param string $actionUrl
     * @return void
     */
    public function processPayment(int $paymentTotal, string $actionUrl): void
    {
        $this->getPaypalForm($paymentTotal, $actionUrl);
    }


}