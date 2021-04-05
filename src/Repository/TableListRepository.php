<?php

namespace App\Repository;

use App\Entity\TableList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method TableList|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableList|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableList[]    findAll()
 * @method TableList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableList::class);
    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
        ->orderBy('a.id', 'ASC');

        return $qb;
    }

    // /**
    //  * @return TableList[] Returns an array of TableList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableList
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
