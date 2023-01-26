<?php

namespace App\Message\Command;

class CreateOrderItem
{
    /**
     * @var integer
     */
    private $cartId;

    /**
     * @var integer
     */
    private $orderId;

    public function __construct(int $cartId, string $orderId)
    {
        $this->cartId = $cartId;
        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getCartId(): int
    {
        return $this->cartId;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }
}