<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param CacheInterface $cache
     * @return Response
     * @Route("/products", name="app_products_page", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, CacheInterface $cache): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(Product::class)->filtered($request->query->all()),
            $request->query->getInt('page', 1),
            8
        );

        $currentPageCacheKey = "products_page_" . $request->query->getInt('page', 1);

        $cache->get($currentPageCacheKey, function (ItemInterface $item) use ($collection) {
            $item->expiresAfter(3600);


            $response = $this->render('products/index.html.twig', [
            'collection' => $collection
        ]);
            $item->set($response);

            return $response;
        });

        return $cache->getItem($currentPageCacheKey)->get();
    }
}
