<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Message\Command\CreateOrder;
use App\Service\StripeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("stripe")
 */
class StripeController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param StripeInterface
     */
    private $stripe;

    public function __construct(LoggerInterface $logger, StripeInterface $stripe)
    {
        $this->logger = $logger;
        $this->stripe = $stripe;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     * @return Response
     *
     * @Route("/success", name="stripe_success")
     */
    public function success(Request $request, EntityManagerInterface $entityManager, MessageBusInterface $messageBus): Response
    {
        try {
            if (!empty($_GET['session_id'])) {
                $stripe = new StripeClient('sk_test_51MbKefG3ggaQ2SPfczpyzWwktZaWBuCxrDG7VFiA6wsPplY7pl3ed0FgtUveC3PGLzfDRVWCzoreLXHi82s9nbya00lbQGXKMd');

                if ($session = $stripe->checkout->sessions->retrieve($_GET['session_id'])) {
                    $entityManager->beginTransaction();
                    $this->addFlash('success', 'The payment was successful');
                    if ($cart = $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id'))) {
                        $messageBus->dispatch(
                            new CreateOrder($cart->getId(), strtoupper($session->status) ?? '', 'Stripe')
                        );
                        $entityManager->remove($cart);
                        $entityManager->flush();
                    }
                    $entityManager->commit();
                }
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $this->logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * @return Response
     * @Route("/error", name="stripe_error")
     */
    public function error(): Response
    {
        $this->addFlash('error', 'The payment was not successful');

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @Route("/pay", name="stripe_pay", methods={"POST"})
     */
    public function pay(Request $request): Response
    {
        try {
            if ($request->getMethod() == 'POST' && !empty($request->get('price'))) {
                if ($checkoutLink = $this->stripe->createOrder($request->getSession()->get('cart_id'))) {
                    return $this->redirect($checkoutLink);
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }
}