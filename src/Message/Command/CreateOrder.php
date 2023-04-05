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

    /**
     * @var string
     */
    private $paymentData;

    /**
     * @var string
     */
    private $invoiceNumber;

    public function __construct(int $cartId, string $paymentStatus, string $paymentMethod, string $paymentData, string $invoiceNumber)
    {
        $this->cartId = $cartId;
        $this->paymentStatus = $paymentStatus;
        $this->paymentMethod = $paymentMethod;
        $this->paymentData = $paymentData;
        $this->invoiceNumber = $invoiceNumber;
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

    /**
     * @return string
     */
    public function getPaymentData(): string
    {
        return $this->paymentData;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }
}