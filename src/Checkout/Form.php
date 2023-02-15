<?php

namespace App\Checkout;

trait Form
{
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
}