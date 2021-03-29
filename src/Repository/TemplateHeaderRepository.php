<?php

namespace App\Repository;

use App\Entity\TemplateHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateHeader[]    findAll()
 * @method TemplateHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateHeader::class);
    }

    // /**
    //  * @return TemplateHeader[] Returns an array of TemplateHeader objects
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
    public function findOneBySomeField($value): ?TemplateHeader
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
