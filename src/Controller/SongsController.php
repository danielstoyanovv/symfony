<?php

namespace App\Controller;

use App\Enum\Flash;
use App\Message\Command\CreateRatingData;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Song;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Psr\Log\LoggerInterface;
use App\Cache\RedisManagerInterface;

/**
 * @Route("/songs")
 */
class SongsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("", name="app_songs")
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
     * @param LoggerInterface $logger
     * @param MessageBusInterface $messageBus
     * @param RedisManagerInterface $redisManager
     * @IsGranted("ROLE_USER")
     * @return Response
     * @Route("/songs_vote", name="app_songs_vote", methods={"POST"})
     */
    public function vote(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, MessageBusInterface $messageBus, RedisManagerInterface $redisManager): Response
    {
        $result = ['result' => 0];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (!empty($request->get('song') && !empty($request->get('rating')))) {
                if ($song = $entityManager->getRepository(Song::class)->find($request->get('song'))) {
                    try {
                        $entityManager->beginTransaction();

                        $messageBus->dispatch(new CreateRatingData(
                            $song->getId(),
                            $this->getUser()->getId(),
                            $request->get('rating')
                        ));

                        $entityManager->commit();
                        $this->addFlash(
                            Flash::SUCCESS,
                            'Vote created'
                        );
                        $result = ['success' => 1];
                        $cache = $redisManager->getAdapter();
                        $cache->clear('home_page');
                    } catch(\Exception $exception) {
                        $entityManager->rollback();
                        $result = ['success' => 0];
                        $logger->error($exception->getMessage());
                        $this->addFlash(
                            Flash::ERROR,
                            'Vote not created'
                        );
                    }
                } else {
                    throw $this->createNotFoundException(sprintf(
                        'Song id: %s did not exists',
                        $request->get('song')
                    ));
                }
            }
        }

        $response = new Response();
        $response->setContent(json_encode($result, true));
        return $response;
    }
}