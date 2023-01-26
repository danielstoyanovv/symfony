<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SendWithTemplate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ProductsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param SendWithTemplate $sendEmail
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @return Response
     * @Route("/products", name="app_products_page", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(Product::class)->filtered($request->query->all()),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('products/index.html.twig', [
            'collection' => $collection
        ]);
    }
}
