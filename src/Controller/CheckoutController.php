<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Checkout\PaypalForm;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    use PaypalForm;

    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @Route("", name="app_checkout_page", methods={"POST"})
     */
    public function index(Request $request, LoggerInterface $logger, UrlGeneratorInterface $urlGenerator): Response
    {
        try {
            if ($request->getMethod() == 'POST') {
                if (!empty($request->get('payment_method')) && !empty($request->get('payment_total'))) {
                    $paymentMethod = $request->get('payment_method');
                    $paymentTotal = $request->get('payment_total');

                    switch ($paymentMethod) {
                        case 'paypal':
                            $this->getPaypalForm($paymentTotal, $urlGenerator->generate('paypal_pay', [], 0));
                            break;
                    }
                }

            }
        } catch (\Exception $exception) {
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }
}