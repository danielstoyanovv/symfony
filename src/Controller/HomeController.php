<?php

namespace App\Controller;

use App\Cache\RedisManagerInterface;
use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RatingData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{
    /**
     * index
     * @param EntityManagerInterface $entityManager
     * @param RedisManagerInterface $redisManager
     * @return Response
     * @Route("", name="app_home_page")
     */
    public function index(EntityManagerInterface $entityManager, RedisManagerInterface $redisManager): Response
    {
        $cache = $redisManager->getAdapter();
        $cache->get('home_page', function (ItemInterface $item) use ($entityManager) {
            $item->expiresAfter(3600);

            $response = $this->render('home/index.html.twig', [
                'rateData' => $entityManager->getRepository(RatingData::class)->getTopRatedData($entityManager),
                'lastAddedSongs' => $entityManager->getRepository(Song::class)->findBy(
                    [
                ],
                    [
                    'id' => 'DESC'
                ],
                    5
                )
            ]);
            $item->set($response);

            return $response;
        });

        return $cache->getItem('home_page')->get();
    }
}
