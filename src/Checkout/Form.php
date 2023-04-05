<?php

namespace App\Checkout;

use App\Entity\Cart;
use App\Service\CalculatesHmac;
use Doctrine\ORM\EntityManagerInterface;

trait Form
{
    use CalculatesHmac;
    /**
     * @param float $paymentTotal
     * @param string $actionUrl
     * @return void
     */
    public function getForm(float $paymentTotal, string $actionUrl): void
    {
        $formHtml = <<<HTML
<form action= '$actionUrl' method='POST' id='form' style='display:none'>
<input type='hidden' name='price' value='$paymentTotal'>
</form>
HTML;
        $form = "<body onload=\"document.getElementById('form').submit();\">";
        $form .= $formHtml;
        echo $form;

        exit;
    }

    /**
     * @param float $paymentTotal
     * @param Cart $cart
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    public function getEpayForm(float $paymentTotal, Cart $cart, EntityManagerInterface $entityManager): void
    {
        $secret = "2F6STHESSOXFW1T4VTGFG5C56KPU2R84XXAXAZQEJ5JMA2XGWL8CFG4TAWYT8BDK";
        $min = "D738009139";
        $actionUrl = "https://devep2.datamax.bg/ep2/epay2_demo/";
        $paymentNumber = $cart->getId();
        $cart->setInvoiceNumber($paymentNumber);
        $entityManager->persist($cart);
        $entityManager->flush();
        $invoice = $paymentNumber;
        $sum = $paymentTotal;
        $expDate = new \DateTime('+' . intval(60) . ' minutes');
        $exp_date = $expDate->format('d.m.Y');
        $descr = $paymentNumber;
        $successActionUrl = $this->urlGenerator->generate('epay_success', [], 0);
        $errorActionUrl = $this->urlGenerator->generate('epay_error', [], 0);

        $data = <<<DATA
MIN={$min}
INVOICE={$invoice}
AMOUNT={$sum}
EXP_TIME={$exp_date}
DESCR={$descr}
DATA;

        $ENCODED = base64_encode($data);
        $CHECKSUM = $this->hmac('sha1', $ENCODED, $secret);

        $formHtml = <<<HTML
<form action="$actionUrl" method="POST" id="form" style="display:inline">
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=ENCODED value="$ENCODED">
<input type=hidden name=CHECKSUM value="$CHECKSUM">
<input type=hidden name=URL_OK value="$successActionUrl">
<input type=hidden name=URL_CANCEL value="$errorActionUrl">
</form>
HTML;

        $form = "<body onload=\"document.getElementById('form').submit();\">";
        $form .= $formHtml;
        echo $form;

        exit;
    }
}