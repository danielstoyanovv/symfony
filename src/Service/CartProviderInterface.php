<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;

interface CartProviderInterface
{
    /**
     * @param int $productId
     * @param float $cartTotal
     * @param int $qty
     * @param float $price
     * @param int|null $cartId
     * @return Cart|false|mixed
     */
    public function addToCart(int $productId, float $cartTotal, int $qty, float $price, int $cartId = null);

    /**
     * @param float $cartTotal
     * @param int $cartId
     * @return Cart|mixed
     */
    public function handleCartData(float $cartTotal, int $cartId = null);

    /**
     * @param Cart $cart
     * @param Product $product
     * @param int $qty
     * @param float $price
     * @return CartItem|mixed
     */
    public function handleCartItemData(Cart $cart, Product $product, int $qty, float $price);

    /**
     * @param CartItem $removeCartItem
     * @return void
     */
    public function removeFromCart(CartItem $removeCartItem): void;
}