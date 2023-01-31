<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @Route("", name="app_admin_articles", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $collection = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/article/index.html.twig', [
            'collection' => $collection,
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @Route("/new", name="app_admin_article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->beginTransaction();
            $article = new Article();
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $articleRepository->add($article);
                $entityManager->commit();
                $this->addFlash('success', "Article was created");
                return $this->redirectToRoute('app_admin_articles', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error($exception->getMessage());
        }

        return $this->renderForm('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @Route("/{id}/edit", name="app_admin_article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->beginTransaction();
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $articleRepository->add($article);
                $entityManager->commit();
                $this->addFlash('success', "Article was updated");
                return $this->redirectToRoute('app_admin_articles', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error($exception->getMessage());
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article);
        }

        return $this->redirectToRoute('app_admin_articles', [], Response::HTTP_SEE_OTHER);
    }
}
