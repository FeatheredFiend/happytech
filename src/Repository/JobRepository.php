<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;



/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }
     
    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
        ->select('j', 'jc')
        ->from('App\Entity\Job', 'j')
        ->leftJoin('j.jobcategory', 'jc')
        ->orderBy('j.id', 'ASC');



        return $qb;
    }

    public function getWithSearchQueryBuilderJobOpen(?string $term, ?int $id): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
        ->select('ja', 'a','j')
        ->from('App\Entity\JobApplicant', 'ja')
        ->leftJoin('ja.applicant', 'a')
        ->leftJoin('ja.job', 'j')
        ->where('ja.applicantresponded = 0 and j.id = ' . $id)
        ->orderBy('ja.id', 'ASC');

        return $qb;

    }


    public function getWithSearchQueryBuilderHomepage(?string $term): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
        ->select('j.id', 'jc.name as category', 'j.name', 'j.description','j.duedate','SUM(CASE WHEN(ja.applicantresponded <> 1) and a.decommissioned = 0 then 1 else 0 end) as applicantcount')
        ->from('App\Entity\JobCategory', 'jc')
        ->leftJoin('jc.jobs', 'j')
        ->leftJoin('j.jobs', 'ja')
        ->leftJoin('ja.applicant', 'a')
        ->where('j.decommissioned = 0 AND ja.emailed = 0')
        ->groupby('j.name')
        ->orderby('j.id','ASC');

        return $qb;

    }








    // /**
    //  * @return Job[] Returns an array of Job objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Job
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
