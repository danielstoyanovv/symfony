<?php

namespace App\MessageHandler\Command;

use App\Entity\Cart;
use App\Entity\Order;
use App\Message\Command\CreateOrder;
use App\Message\Command\CreateOrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateOrderHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(EntityManagerInterface  $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateOrder $createOrder)
    {
        if ($cart = $this->entityManager->getRepository(Cart::class)->find($createOrder->getCartId())) {
            $order =  new Order();
            $order->setTotal($cart->getTotal())
                ->setStatus($createOrder->getPaymentStatus())
                ->setPaymentMethod($createOrder->getPaymentMethod())
                ->setPaymentData($createOrder->getPaymentData())
                ->setInvoiceNumber($createOrder->getInvoiceNumber());

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->messageBus->dispatch(new CreateOrderItem($cart->getId(), $order->getId()));
        }
    }
}