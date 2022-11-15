<?php

namespace App\Repository;

use App\Entity\RatingData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RatingData|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingData|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingData[]    findAll()
 * @method RatingData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingData::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RatingData $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RatingData $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getTopRatedData(EntityManagerInterface $entityManager): array
    {
        $result = $entityManager->getConnection()->fetchAllAssociative('
         SELECT song.id, song.name as song_name, AVG(rating_data.rating) as avg_rating, COUNT(rating_data.song_id) AS count
            FROM song
            JOIN rating_data
            WHERE song.id = rating_data.song_id
            GROUP BY song.id
            ORDER BY avg_rating DESC
            LIMIT 5
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
