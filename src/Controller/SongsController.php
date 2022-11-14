<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Song;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RatingData;
use App\Form\RatingDataType;
use Doctrine\ORM\EntityManagerInterface;

class SongsController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @return Response
     * 
     * @Route("/songs", name="app_songs")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('songs/index.html.twig', [
            'rateData' => $entityManager->getRepository(Song::class)->getSongsRateData($entityManager),
            'songs' => $entityManager->getRepository(Song::class)->findAll()
        ]);
    }

    /**
     * vote
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * 
     * @Route("/songs_vote", name="app_songs_vote")
     */

    public function vote(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $rating = new RatingData();
            $form = $this->createForm(RatingDataType::class, $rating);
            $form->handleRequest($request);
                
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($rating);
                $entityManager->flush();
                return $this->redirectToRoute("app_songs");
            }
            
        } catch(\Exception $e) {
            $this->addFlash(
                'error', 'Something happened! Vote Is not created'
            );
            return $this->redirectToRoute("app_songs");
        }
        return $this->renderForm('songs/vote.html.twig', [
            'form' => $form
        ]);
    }

}
