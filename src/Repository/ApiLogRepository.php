<?php

namespace App\Repository;

use App\Entity\ApiLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiLog[]    findAll()
 * @method ApiLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiLog::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ApiLog $entity, bool $flush = true): void
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
    public function remove(ApiLog $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ApiLog[] Returns an array of ApiLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiLog
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param array $filters
     * @return QueryBuilder
     */
    public function filtered(array $filters = []): QueryBuilder
    {
        $builder = $this
            ->createQueryBuilder('api_log')
            ->addOrderBy('api_log.id', 'desc');

        if (!empty($filters['api_name'])) {
            $builder->andWhere('api_log.apiName LIKE :name')
                ->setParameter('name', "%{$filters['api_name']}%");
        }

        return $builder;
    }
}
