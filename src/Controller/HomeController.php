<?php

namespace App\Controller;

use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RatingData;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("", name="app_home_page")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('home/index.html.twig', [
            'rateData' => $entityManager->getRepository(RatingData::class)->getTopRatedData($entityManager),
            'lastAddedSongs' => $entityManager->getRepository(Song::class)->findBy([
            ], [
                'id' => 'DESC'
            ],
                5
            )
        ]);
    }
}
