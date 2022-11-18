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
use Psr\Log\LoggerInterface;

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
     * @IsGranted("ROLE_USER")
     * @return Response
     * @Route("/songs_vote", name="app_songs_vote", methods={"POST"})
     */
    public function vote(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $result = ['result' => 0];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
            if (!empty($request->get('song') && !empty($request->get('rating')))) {
                if ($song = $entityManager->getRepository(Song::class)->find($request->get('song'))) {
                    try {
                        $rating = new RatingData();
                        $rating->setSong($song);
                        $rating->setUser($this->getUser());
                        $rating->setRating($request->get('rating'));
                        $entityManager->persist($rating);
                        $entityManager->flush();

                        $this->addFlash(
                           Flash::SUCCESS,
                            'Vote created'
                        );
                        $result = ['success' => 1];

                    } catch(\Exception $exception) {
                        $result = ['success' => 0];
                        $logger->error($exception->getMessage());
                        $this->addFlash(
                            Flash::ERROR,
                            'Vote not created'
                        );
                    }
                }

            }
        }

        $response = new Response();
        $response->setContent(json_encode($result, true));
        return $response;
    }
}