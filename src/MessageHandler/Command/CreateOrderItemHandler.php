<?php

namespace App\MessageHandler\Command;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Message\Command\CreateOrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateOrderItemHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateOrderItem $createOrderItem)
    {
        if ($cart = $this->entityManager->getRepository(Cart::class)->find($createOrderItem->getCartId())) {
            if ($order = $this->entityManager->getRepository(Order::class)->find($createOrderItem->getOrderId())) {
                foreach ($cart->getCartItem() as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->setQty($item->getQty())
                        ->setPrice($item->getPrice())
                        ->setProduct($item->getProduct())
                        ->setOrders($order);

                    $this->entityManager->persist($orderItem);
                    $this->entityManager->flush();
                }
            }
        }
    }
}