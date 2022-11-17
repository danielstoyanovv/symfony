<?php

namespace App\Controller\User;

use App\Entity\RatingData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    /**
     * index
     * @return Response
     * @IsGranted("ROLE_USER")
     * @Route("/profile", name="app_profile_page", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('user/profile.html.twig', [
        ]);
    }

    /**
     * index
     * @return Response
     * @IsGranted("ROLE_USER")
     * @Route("/songs_voted_for", name="app_profile_songs_voted_for_page", methods={"GET"})
     */
    public function songsVotedFor(EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/songs_voted_for.html.twig', [
            'rateData' => $entityManager->getRepository(RatingData::class)->findBy(['user' => $this->getUser()])
        ]);
    }
}