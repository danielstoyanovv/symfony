<?php

namespace App\MessageHandler\Command;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Message\Command\CreateOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateOrderHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateOrder $createOrder)
    {
        if ($cart = $this->entityManager->getRepository(Cart::class)->find($createOrder->getCartId())) {
            $order =  new Order();
            $order->setTotal($cart->getTotal())
                ->setStatus( $createOrder->getPaymentStatus())
                ->setPaymentMethod($createOrder->getPaymentMethod());

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            foreach ($cart->getCartItem() as $item) {
                $orderItem = new OrderItem();
                $orderItem->setQty($item->getQty())
                    ->setPrice($item->getPrice())
                    ->setProduct($item->getProduct())
                    ->setOrders($order);

                $this->entityManager->persist($orderItem);
                $this->entityManager->flush();
            }

            $this->entityManager->remove($cart);
            $this->entityManager->flush();
        }
    }
}