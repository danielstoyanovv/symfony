<?php

namespace App\Controller\Admin;

use App\Entity\ApiLog;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("", name="app_admin_orders", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(Order::class)->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/orders/index.html.twig', [
            'collection' => $collection
        ]);
    }

    /**
     * @param ApiLog $apiLog
     * @return Response
     * @Route("/{id}/details", name="app_admin_orders_details", methods={"GET"})
     */
    public function details(Order $order): Response
    {
        return $this->render('admin/orders/details.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @param Order $order
     * @return Response
     * @Route("/{id}/stripe_refund_form", name="app_admin_orders_stripe_refund_form", methods={"GET"})
     */
    public function stripeRefundForm(Order $order): Response
    {
        return $this->render('admin/orders/stripeRefundForm.html.twig', [
            'order' => $order
        ]);
    }
}