<?php

namespace App\Repository;

use App\Entity\FeedbackResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method FeedbackResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedbackResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedbackResponse[]    findAll()
 * @method FeedbackResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedbackResponse::class);
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
    //  * @return FeedbackResponse[] Returns an array of FeedbackResponse objects
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
    public function findOneBySomeField($value): ?FeedbackResponse
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
