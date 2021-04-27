<?php

namespace App\Repository;

use App\Entity\TemplateHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
        ->select('th', 'u','ft')
        ->from('App\Entity\TemplateHeader', 'th')
        ->leftJoin('th.user', 'u')
        ->leftJoin('th.feedbacktype', 'ft')
        ->orderBy('th.id', 'ASC');

        return $qb;
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
