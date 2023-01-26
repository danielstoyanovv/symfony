<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;

class CartProvider implements CartProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $productId
     * @param float $cartTotal
     * @param int $qty
     * @param float $price
     * @param int|null $cartId
     * @return Cart|false|mixed
     */
    public function addToCart(int $productId, float $cartTotal, int $qty, float $price, int $cartId = null)
    {
        if ($product = $this->entityManager->getRepository(Product::class)->find($productId)) {
            $cart = $this->handleCartData($cartTotal, $cartId);
            $this->handleCartItemData($cart, $product, $qty, $price);

            return $cart;
        }

        return false;
    }

    /**
     * @param float $cartTotal
     * @param int $cartId
     * @return Cart|mixed
     */
    public function handleCartData(float $cartTotal, int $cartId = null)
    {
        if (!empty($cartId)) {
            if ($cart = $this->entityManager->getRepository(Cart::class)->find($cartId)) {
                $currentTotal = $cart->getTotal();
                $cart->setTotal($currentTotal + $cartTotal);
                $this->entityManager->flush();
                return $cart;
            }
        }

        $cart = new Cart();
        $cart->setTotal($cartTotal);

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }

    /**
     * @param Cart $cart
     * @param Product $product
     * @param int $qty
     * @param float $price
     * @return CartItem|mixed
     */
    public function handleCartItemData(Cart $cart, Product $product, int $qty, float $price)
    {
        foreach ($cart->getCartItem() as $item) {
            if ($product->getId() == $item->getProduct()->getId()) {
                $currentQty = $item->getQty();
                $item->setQty($currentQty + $qty);
                $this->entityManager->flush();
                return $item;
            }
        }

        $cartItem = new CartItem();
        $cartItem
            ->setPrice($price)
            ->setProduct($product)
            ->setQty($qty)
            ->setCart($cart);

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        return $cartItem;
    }

    /**
     * @param CartItem $removeCartItem
     * @return void
     */
    public function removeFromCart(CartItem $removeCartItem): void
    {
        $currentCartTotal = $removeCartItem->getCart()->getTotal();
        $newCartTotal = $currentCartTotal - $removeCartItem->getQty() * $removeCartItem->getPrice();

        $removeCartItem->getCart()->setTotal($newCartTotal);
        $this->entityManager->flush();

        $this->entityManager->remove($removeCartItem);
        $this->entityManager->flush();
    }
}