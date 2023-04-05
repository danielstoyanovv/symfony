<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Message\Command\CreateOrder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Checkout\Form;

/**
 * @IsGranted("ROLE_USER")
 * @Route("epay")
 */
class EpayController extends AbstractController
{
    use Form;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(LoggerInterface $logger, UrlGeneratorInterface $urlGenerator)
    {
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     * @return Response
     *
     * @Route("/success", name="epay_success")
     */
    public function success(Request $request, EntityManagerInterface $entityManager, MessageBusInterface $messageBus): Response
    {
        try {
            $entityManager->beginTransaction();

            $this->addFlash('success', 'The payment was successful');

            if ($cart = $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id'))) {
                $messageBus->dispatch(
                    new CreateOrder($cart->getId(), '', 'Epay', '', $cart->getInvoiceNumber())
                );
                $entityManager->remove($cart);
                $entityManager->flush();
            }


            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $this->logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * pay
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/pay", name="epay_pay")
     */
    public function pay(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            if ($request->getMethod() == 'POST' && !empty($request->get('price'))) {
                if ($cart = $entityManager->getRepository(Cart::class)->find($request->getSession()->get('cart_id'))) {
                    $this->getEpayForm($request->get('price'), $cart, $entityManager);
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * error
     * @param Request $request
     * @return Response
     * @Route("/error", name="epay_error")
     */
    public function error(Request $request): Response
    {
        $this->addFlash('error', 'The payment was not successful');

        return $this->redirectToRoute('app_home_page');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/notification", name="epay_notification")
     */
    public function notification(Request $request, EntityManagerInterface $entityManager)
    {
        $STATUS_PAID = 'PAID';
        $STATUS_DENIED = 'DENIED';
        $STATUS_EXPIRED = 'EXPIRED';

        $ENCODED = $request->request->get('encoded');
        $CHECKSUM = $request->request->get('checksum');


        if ($ENCODED && $CHECKSUM) {
            $hmac = $this->hmac('sha1', $ENCODED, "2F6STHESSOXFW1T4VTGFG5C56KPU2R84XXAXAZQEJ5JMA2XGWL8CFG4TAWYT8BDK");

            if ($hmac == $CHECKSUM) {
                $data = base64_decode($ENCODED, true);
                $lines = explode("\n", $data);
                $info = '';

                foreach ($lines as $line) {
                    if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) {
                        $invoice = $regs[1];
                        $status = $regs[2];
                        $pay_date = $regs[4];
                        $stan = $regs[5];
                        $bcode = $regs[6];

                        /** @var Order $payment */
                        $order = $entityManager->getRepository(Order::class)->findOneBy(['invoiceNumber', $invoice]);

                        if ($order) {
                            switch ($status) {
                                case $STATUS_PAID:
                                    $info .= "INVOICE=$invoice:STATUS=OK\n";
                                    $order->setStatus($STATUS_PAID);
                                    break;
                                case $STATUS_DENIED:
                                    $info .= "INVOICE=$invoice:STATUS=OK\n";
                                    break;
                                case $STATUS_EXPIRED:
                                    $info .= "INVOICE=$invoice:STATUS=OK\n";
                                    break;
                                default:
                                    $info .= "INVOICE=$invoice:STATUS=ERR\n";
                            }
                        } else {
                            $info .= "INVOICE=$invoice:STATUS=NO\n";
                        }

                        $entityManager->flush();
                    }
                }

                echo $info, "\n";
                exit;
            } else {
                echo "ERR=Not valid CHECKSUM\n";
                exit;
            }
        }

        echo "ERR=Missing POST parameters\n";
        exit;
    }
}