<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Message\Command\CreateOrder;
use App\Service\PaypalInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("paypal")
 */
class PaypalController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PaypalInterface
     */
    private $paypal;

    public function __construct(LoggerInterface $logger, PaypalInterface $paypal)
    {
        $this->logger = $logger;
        $this->paypal = $paypal;
    }

    /**
     * @param Request $request
     * @param string $paypalApiUrl
     * @param string $paypaAuthorizationCode
     * @param
     * @return Response
     *
     * @Route("/success", name="paypal_success")
     */
    public function success(Request $request, string $paypalApiUrl, string $paypaAuthorizationCode, EntityManagerInterface $entityManager, MessageBusInterface $messageBus): Response
    {
        try {
            $entityManager->beginTransaction();

            if ($request->get('token') && $request->get('PayerID')) {
                $this->addFlash('success', 'The payment was successful');
                $captureResponse = $this->paypal->capture($request->get('token'), $paypalApiUrl, $this->getToken($request, $paypalApiUrl, $paypaAuthorizationCode));
                if ($cart = $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id'))) {
                    $messageBus->dispatch(
                        new CreateOrder($cart->getId(), $captureResponse['status'] ?? '', 'Paypal', '')
                    );
                    $entityManager->remove($cart);
                    $entityManager->flush();
                }
            }

            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $this->logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * index
     * @return Response
     * @Route("/error", name="paypal_error")
     */
    public function error(Request $request): Response
    {
        $this->addFlash('error', 'The payment was not successful');

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * @param Request $request
     * @param string $paypalApiUrl
     * @param string $paypalUrl
     * @param string $paypaAuthorizationCode
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @Route("/pay", name="paypal_pay", methods={"POST"})
     */
    public function pay(Request $request, string $paypalApiUrl, string $paypalUrl, string $paypaAuthorizationCode): Response
    {
        try {
            if ($request->getMethod() == 'POST' && !empty($request->get('price'))) {
                if ($token = $this->getToken($request, $paypalApiUrl, $paypaAuthorizationCode)) {
                    $orderResponse = $this->paypal->createOrder($token, $request->get('price'), $paypalApiUrl);
                    if (!empty($orderResponse['id'])) {
                        return $this->redirect($this->paypal->getCheckoutNowUrl($orderResponse['id'], $paypalUrl));
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $this->redirectToRoute('app_home_page');
    }

    /**
     * @param Request $request
     * @param string $paypalApiUrl
     * @param string $paypaAuthorizationCode
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getToken(Request $request, string $paypalApiUrl, string $paypaAuthorizationCode): string
    {
        if ($request->getSession()->get('token')) {
            return $request->getSession()->get('token');
        }

        $token = $this->paypal->getToken($paypalApiUrl, $paypaAuthorizationCode);
        $request->getSession()->set('token', $token);

        return  $token;
    }
}