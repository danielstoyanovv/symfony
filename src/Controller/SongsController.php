<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Song;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\RatingData;

class SongsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     * 
     * @Route("/songs", name="app_songs")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager('default');
        return $this->render('songs/index.html.twig', [
            'rateData' => $this->getRateData($doctrine),
            'songs' => $entityManager->getRepository(Song::class)->findAll(),
        ]);
    }

    /**
     * vote
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     * 
     * @Route("/songs_vote", name="app_songs_vote")
     */
    
    public function vote(Request $request, ManagerRegistry $doctrine): Response
    {
        try {
            $form = $this->getForm($doctrine);

            $form->handleRequest($request);
                
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $rating = new RatingData();
                $rating->setCustomerName($data['customer_name']);
                $rating->setCustomerEmail($data['customer_email']);
                $rating->setRating($data['rating']);
                $rating->setSongId($data['song_id']);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($rating);
                $em->flush();
                return $this->redirectToRoute("app_songs");
            }
            
        } catch(\Exception $e) {
            $this->addFlash(
                'error', 'Something happened! Vote Is not created'
            );
            return $this->redirectToRoute("app_songs");
        }
        return $this->render('songs/vote.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Get songs
     * @param ManagerRegistry $doctrine
     * return array
     */
    private function getSongs(ManagerRegistry $doctrine): array
    {
        $songsIdNames = [];
        $songs = $doctrine->getManager('default')->getRepository(Song::class);
        if ($songs->findAll()) {
            foreach ($songs->findAll() as $song) {
                $songsIdNames[$song->getName()] = $song->getId();
            }
        }
       return $songsIdNames;
    }

    /**
     * get form
     * @param ManagerRegistry $doctrine
     * @return \Symfony\Component\Form\Form
     */
    private function getForm(ManagerRegistry $doctrine)
    {
        $ratings = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];
        $form = $this->createFormBuilder()
        ->add('customer_name', TextType::class)
        ->add('customer_email', EmailType::class)
        ->add('rating', ChoiceType::class, array(
            'choices' => $ratings,
            'multiple' => false
        ))
        ->add('song_id', ChoiceType::class, array(
            'choices' => $this->getSongs($doctrine),
            'multiple' => false,
            'label' => 'Song'
        ))
        ->add('send', SubmitType::class)
        ->getForm();
        return $form;
    }

    /**
     * get rate data
     * @param ManagerRegistry $doctrine
     * @return array
     */
    private function getRateData(ManagerRegistry $doctrine): array
    {
        $result = $doctrine->getConnection()->fetchAllAssociative('
            SELECT song.id,  AVG(rating_data.rating) as avg_rating, COUNT(rating_data.song_id) AS count
            FROM song 
            JOIN rating_data
            WHERE song.id = rating_data.song_id
            GROUP BY song.id
        ');
        $rateResult = [];
        if ($result) {
            foreach ($result as $rate) {
                $rateResult[$rate['id']] = $rate;
            }
        }
        return $rateResult;

    }
}
