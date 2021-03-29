<?php

namespace App\Repository;

use App\Entity\FeedbackResponseStatement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FeedbackResponseStatement|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedbackResponseStatement|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedbackResponseStatement[]    findAll()
 * @method FeedbackResponseStatement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackResponseStatementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedbackResponseStatement::class);
    }

    // /**
    //  * @return FeedbackResponseStatement[] Returns an array of FeedbackResponseStatement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeedbackResponseStatement
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
