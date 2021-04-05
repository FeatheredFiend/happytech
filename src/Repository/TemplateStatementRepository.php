<?php

namespace App\Repository;

use App\Entity\TemplateStatement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method TemplateStatement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateStatement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateStatement[]    findAll()
 * @method TemplateStatement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateStatementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateStatement::class);
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
    //  * @return TemplateStatement[] Returns an array of TemplateStatement objects
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
    public function findOneBySomeField($value): ?TemplateStatement
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
