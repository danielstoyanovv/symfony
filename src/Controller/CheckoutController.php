<?php

namespace App\Controller;

use App\Service\Payment\PaymentInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use App\Service\Payment\Paypal;
use App\Service\Payment\Stripe;
use App\Service\Payment\Epay;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @param UrlGeneratorInterface $urlGenerator
     * @param ContainerBagInterface $containerBag
     * @return Response
     * @Route("", name="app_checkout_page", methods={"POST"})
     */
    public function index(Request $request, LoggerInterface $logger, UrlGeneratorInterface $urlGenerator, ContainerBagInterface $containerBag): Response
    {
        try {
            if ($request->getMethod() == 'POST') {
                if (!empty($request->get('payment_method')) && !empty($request->get('payment_total'))) {
                    $paymentMethod = $request->get('payment_method');
                    $paymentTotal = $request->get('payment_total');

                    switch ($paymentMethod) {
                        case "paypal":
                            $paymentClassInstance = new Paypal($urlGenerator);
                            break;
                        case "stripe":
                            $paymentClassInstance = new Stripe($urlGenerator);
                            break;
                        case "epay":
                            $paymentClassInstance = new Epay($urlGenerator);
                            break;
                    }

                    if ($paymentClassInstance instanceof PaymentInterface) {
                        return $paymentClassInstance->processPayment($paymentTotal);
                    }
                    throw $this->createNotFoundException(sprintf(
                        'Class: %s is not a valid payment class',
                        $paymentClassInstance
                    ));
                }
            }
        } catch (\Exception $exception) {
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }
}