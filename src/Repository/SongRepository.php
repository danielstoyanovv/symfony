<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Song $entity, bool $flush = true): void
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
    public function remove(Song $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * get rate data
     * @param EntityManagerInterface $doctrine
     * @return array
     */
    public function getSongsRateData(EntityManagerInterface $entityManager): array
    {
        $result = $entityManager->getConnection()->fetchAllAssociative('
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

    /**
     * @param array $filters
     * @return QueryBuilder
     */
    public function filtered(array $filters = []): QueryBuilder
    {
        $builder = $this
            ->createQueryBuilder('song')
            ->orderBy('song.id', 'desc');

        if (!empty($filters['name'])) {
            $builder->andWhere('song.name LIKE :name')
                ->setParameter('name', "%{$filters['name']}%");
        }

        return $builder;
    }
}
