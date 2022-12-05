<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/cart", name="app_cart_page", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $request->getSession()->get('cart_id') ? $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id')) : null
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/add_to_cart", name="app_add_to_cart_page", methods={"POST"})
     */
    public function addToCart(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            if ($request->getMethod() == 'POST') {
                if (!empty($request->get('product')) && !empty($request->get('price')) && !empty($request->get('qty'))) {
                    $productId = $request->get('product');
                    $price = $request->get('price');
                    $qty = $request->get('qty');
                    $cartTotal = $price * $qty;

                    if ($product = $entityManager->getRepository(Product::class)->find($productId)) {
                       $cart = $this->handleCartData($request,  $entityManager, $cartTotal);
                       $this->handleCartItemData($cart, $entityManager, $product, $qty, $price);

                        $request->getSession()->set('cart_id', $cart->getId());
                    }

                }

            }
        } catch (\Exception $exception) {
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_cart_page');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param int $cartTotal
     * @return Cart|mixed|object
     */
    private function handleCartData(Request $request, EntityManagerInterface $entityManager, int $cartTotal)
    {
        if (!empty($request->getSession()->get('cart_id'))) {
            if ($cart = $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id'))) {
                $currentTotal = $cart->getTotal();
                $cart->setTotal($currentTotal + $cartTotal);
                $entityManager->flush();
                return $cart;
            }
        }

        $cart = new Cart();
        $cart->setTotal($cartTotal);

        $entityManager->persist($cart);
        $entityManager->flush();

        return $cart;
    }

    /**
     * @param Cart $cart
     * @param EntityManagerInterface $entityManager
     * @param Product $product
     * @param int $qty
     * @param int $price
     * @return CartItem|mixed
     */
    private function handleCartItemData(Cart $cart, EntityManagerInterface $entityManager, Product $product, int $qty, int $price)
    {
        foreach ($cart->getCartItem() as $item) {
            if ($product->getId() == $item->getProduct()->getId()) {
                $currentQty = $item->getQty();
                $item->setQty($currentQty + $qty);
                $entityManager->flush();
                return $item;
            }
        }

        $cartItem = new CartItem();
        $cartItem
            ->setPrice($price)
            ->setProduct($product)
            ->setQty($qty)
            ->setCart($cart);

        $entityManager->persist($cartItem);
        $entityManager->flush();

        return $cartItem;
    }
}
