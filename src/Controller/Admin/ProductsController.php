<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\Product;
use App\Enum\Flash;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use \Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\UploaderInterface;

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
     * @param LoggerInterface $logger
     * @param UploaderInterface $uploader
     * @param MessageBusInterface $messageBus
     * @return Response
     * @Route("/create", name="app_admin_products_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, LoggerInterface $logger, UploaderInterface $uploader, MessageBusInterface $messageBus): Response
    {
        try {
            $manager->beginTransaction();

            $product = new Product();

            $form = $this->createForm(ProductType::class, $product);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('image')->getData()) {
                    if ($imageName = $uploader->upload($form->get('image')->getData(), $this->getParameter('kernel.project_dir'), 'image', $messageBus)) {
                        if ($image = $manager->getRepository(File::class)->findOneBy(['name' => $imageName])) {
                            $product->addImage($image);
                        }
                    }
                }
                $manager->persist($product);
                $manager->flush();
                $manager->commit();

                $this->addFlash('success', "Product was created");

                return $this->redirectToRoute('app_admin_products');
            }

        } catch (\Exception $exception) {
            $manager->rollback();
            $logger->error($exception->getMessage());
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
     * @param LoggerInterface $logger
     * @param UploaderInterface $uploader
     * @param MessageBusInterface $messageBus
     * @return Response
     * @Route("/update/{slug}", name="app_admin_products_update", methods={"GET", "POST"})
     */
    public function update(Product $product, Request $request, EntityManagerInterface $manager, LoggerInterface $logger, UploaderInterface $uploader, MessageBusInterface $messageBus): Response
    {
        try {
            $manager->beginTransaction();

            $form = $this->createForm(ProductType::class, $product);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('image')->getData()) {
                    if ($imageName = $uploader->upload($form->get('image')->getData(), $this->getParameter('kernel.project_dir'), 'image', $messageBus)) {
                        if ($image = $manager->getRepository(File::class)->findOneBy(['name' => $imageName])) {
                            $product->addImage($image);
                        }
                    }
                }
                $manager->flush();
                $manager->commit();

                $this->addFlash('success', "Product was updated");

                return $this->redirectToRoute('app_admin_products');
            }
        } catch (\Exception $exception) {
            $manager->rollback();
            $logger->error($exception->getMessage());
        }

        return $this->renderForm('admin/products/update.html.twig', [
            'form' => $form,
            'product' => $product
        ]);
    }

    /**
     * create order
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @Route("/create_position", name="app_admin_products_create_position", methods={"GET"})
     */
    public function createPosition(Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('admin/products/create_position.html.twig', [
            'collection' => $entityManager->getRepository(Product::class)->findBy([
            ], [
                'position' => 'asc'
            ]),
            'createOrderAction' => $urlGenerator->generate('app_admin_products_save_position', [], 0)
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/save_position", name="app_admin_products_save_position", methods={"POST"})
     */
    public function savePosition(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->beginTransaction();
            $result = ['result' => 0];

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                if (!empty($request->get('products'))) {
                    foreach ($request->get('products') as $position => $productId) {
                        if ($product = $entityManager->getRepository(Product::class)->find($productId)) {
                            $product->setPosition($position);
                            $entityManager->flush();
                        }
                    }
                    $entityManager->commit();
                    $this->addFlash(
                        Flash::SUCCESS,
                        'Products position was updated'
                    );
                    $result = ['success' => 1];


                }
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error($exception->getMessage());
            $this->addFlash(
                Flash::ERROR,
                'Products position was not updated'
            );
        }

        $response = new Response();
        $response->setContent(json_encode($result, true));
        return $response;
    }

}