<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use \Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/products")
 */
class ProductsController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("", name="app_admin_products", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(Product::class)->filtered($request->query->all()),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/products/index.html.twig', [
            'collection' => $collection
        ]);
    }

    /**
     * create
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @Route("/create", name="app_admin_products_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', "Product was created");

            return $this->redirectToRoute('app_admin_products');
        }


        return $this->renderForm('admin/products/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * update
     * @param Product $product
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @Route("/update/{slug}", name="app_admin_products_update", methods={"GET", "POST"})
     */
    public function update(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "Product was updated");

            return $this->redirectToRoute('app_admin_products');
        }


        return $this->renderForm('admin/products/update.html.twig', [
            'form' => $form
        ]);
    }

}