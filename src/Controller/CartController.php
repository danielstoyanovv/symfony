<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\Flash;
use App\Service\CartProviderInterface;

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
     * @param LoggerInterface $logger
     * @param CartProviderInterface $cartProvider
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/add_to_cart", name="app_add_to_cart_page", methods={"POST"})
     */
    public function addToCart(Request $request, LoggerInterface $logger, CartProviderInterface $cartProvider, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->beginTransaction();

            if ($request->getMethod() == 'POST') {
                if (!empty($request->get('product')) && !empty($request->get('price')) && !empty($request->get('qty'))) {
                    $productId = $request->get('product');
                    $price = $request->get('price');
                    $qty = $request->get('qty');
                    $cartTotal = $price * $qty;

                    if ($cart = $cartProvider->addToCart($productId, $cartTotal, $qty, $price, $request->getSession()->get('cart_id'))) {
                        $request->getSession()->set('cart_id', $cart->getId());
                    }

                    $entityManager->commit();
                    $this->addFlash(Flash::SUCCESS, 'Product was added in cart');
                }
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_cart_page');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param CartProviderInterface $cartProvider
     * @return Response
     * @Route("/remove_from_cart", name="app_remove_from_cart_page", methods={"POST"})
     */
    public function removeFromCart(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, CartProviderInterface $cartProvider): Response
    {
        try {
            $entityManager->beginTransaction();

            if ($request->getMethod() == 'POST') {
                if (!empty($request->get('cart_item_id'))) {
                    if ($removeCartItem = $entityManager->getRepository(CartItem::class)->find($request->get('cart_item_id'))) {

                        if ($removeCartItem->getCart()->getId() != $request->getSession()->get('cart_id')) {
                            throw $this->createNotFoundException(sprintf(
                                'Product name : %s can\'t be removed',
                                $removeCartItem->getProduct()->getName()
                            ));
                        }
                        $cartProvider->removeFromCart($removeCartItem);
                        $entityManager->commit();
                    }

                    $this->addFlash(Flash::SUCCESS, 'Product was removed from cart');
                }
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_cart_page');
    }
}