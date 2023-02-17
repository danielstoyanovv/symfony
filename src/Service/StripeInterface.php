<?php

namespace App\Service;

interface StripeInterface
{
    /**
     * @param int $cartId
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createOrder(int $cartId): string;

    /**
     * @param int $cartId
     * @return array
     */
    public function getLineItemsData(int $cartId): array;

    /**
     * @param string $paymentNumber
     * @param float $amount
     * @return mixed|\Stripe\Refund|void
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function refund(string $paymentNumber, float $amount);
}