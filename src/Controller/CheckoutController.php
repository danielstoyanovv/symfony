<?php

namespace App\Controller;

use App\Service\Payment\PaymentInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
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

                    $paymentClass = 'App' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'Payment'
                        . DIRECTORY_SEPARATOR . ucfirst($paymentMethod);
                    if (class_exists($paymentClass)) {
                        $paymentClassInstance = new $paymentClass($urlGenerator);

                        if ($paymentClassInstance instanceof PaymentInterface) {
                            return $paymentClassInstance->processPayment($paymentTotal);
                        }
                        throw $this->createNotFoundException(sprintf(
                            'Class: %s is not a valid payment class',
                            $paymentClass
                        ));
                    }
                }
            }
        } catch (\Exception $exception) {
            $logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }
}