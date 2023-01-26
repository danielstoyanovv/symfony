<?php

namespace App\Controller\Admin;

use App\Entity\ApiLog;
use App\Enum\Flash;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/api_logs")
 */
class ApiLogsController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("", name="app_admin_api_logs", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(ApiLog::class)->filtered($request->query->all()),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/api_logs/index.html.twig', [
            'collection' => $collection
        ]);
    }

    /**
     * @param ApiLog $apiLog
     * @return Response
     * @Route("/{id}/details", name="app_admin_api_logs_details", methods={"GET"})
     */
    public function details(ApiLog  $apiLog): Response
    {
        return $this->render('admin/api_logs/details.html.twig', [
            'apiLog' => $apiLog
        ]);
    }
}