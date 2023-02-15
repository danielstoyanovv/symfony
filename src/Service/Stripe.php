<?php

namespace App\Service;

use App\Entity\Cart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;

class Stripe implements StripeInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $cartId
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createOrder(int $cartId): string
    {
        $stripe = new StripeClient('sk_test_51MbKefG3ggaQ2SPfczpyzWwktZaWBuCxrDG7VFiA6wsPplY7pl3ed0FgtUveC3PGLzfDRVWCzoreLXHi82s9nbya00lbQGXKMd');

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                $this->getLineItemsData($cartId)
            ],
            'mode' => 'payment',
            'success_url' => $this->urlGenerator->generate('stripe_success', [], 0) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->urlGenerator->generate('stripe_error', [], 0)
        ]);

        return $checkout_session->url;
    }

    /**
     * @param int $cartId
     * @return array
     */
    public function getLineItemsData(int $cartId): array
    {
        $data = [];

        if ($cart = $this->entityManager->getRepository(Cart::class)->find($cartId)) {
            foreach ($cart->getCartItem() as $item) {
                $data[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->getProduct()->getName(),
                        ],
                        'unit_amount' => $item->getProduct()->getPrice() * 100,
                    ],
                    'quantity' => $item->getQty()
                ];
            }
        }

        return $data;
    }
}