<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \App\Service\SendWithTemplate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Cache\ItemInterface;

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
        $cacheClient = RedisAdapter::createConnection('redis://localhost:6379');
        $cache = new RedisTagAwareAdapter($cacheClient);

        $cache->get('products_page', function (ItemInterface $item) use ($request, $entityManager, $paginator)  {
            $item->expiresAfter(3600);

            $collection = $paginator->paginate(
                $entityManager->getRepository(Product::class)->filtered($request->query->all()),
                $request->query->getInt('page', 1),
                8
            );

            $response = $this->render('products/index.html.twig', [
                'collection' => $collection
            ]);
            $item->set($response);

            return $response;
        });

        return $cache->getItem('products_page')->get();
    }
}
