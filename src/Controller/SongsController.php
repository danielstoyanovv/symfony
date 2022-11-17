<?php

namespace App\Controller;

use App\Enum\Flash;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Song;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RatingData;
use App\Form\RatingDataType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SongsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/songs", name="app_songs")
     */
    public function index(Request  $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $collection = $paginator->paginate(
            $entityManager->getRepository(Song::class)->filtered($request->query->all()),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('songs/index.html.twig', [
            'rateData' => $entityManager->getRepository(Song::class)->getSongsRateData($entityManager),
            'songs' => $collection
        ]);
    }

    /**
     * vote
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_USER")
     * @Route("/songs_vote", name="app_songs_vote")
     */
    public function vote(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $rating = new RatingData();
            $form = $this->createForm(RatingDataType::class, $rating);
            $form->handleRequest($request);
                
            if ($form->isSubmitted() && $form->isValid()) {
                $rating->setUser($this->getUser());
                $entityManager->persist($rating);
                $entityManager->flush();
                $this->addFlash(
                    Flash::SUCCESS,
                    'Vote created!'
                );
                return $this->redirectToRoute("app_songs");
            }
            
        } catch(\Exception $e) {
            $this->addFlash(
                Flash::ERROR,
                 'Something happened! Vote Is not created'
            );
            return $this->redirectToRoute("app_songs");
        }
        return $this->renderForm('songs/vote.html.twig', [
            'form' => $form
        ]);
    }

}
