<?php

namespace App\Message\Command;

class CreateOrder
{
    /**
     * @var integer
     */
    private $cartId;

    /**
     * @var string
     */
    private $paymentStatus;

    /**
     * @var string
     */
    private $paymentMethod;

    public function __construct(int $cartId, string $paymentStatus, string $paymentMethod)
    {
        $this->cartId = $cartId;
        $this->paymentStatus = $paymentStatus;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return int
     */
    public function getCartId(): int
    {
        return $this->cartId;
    }

    /**
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
}