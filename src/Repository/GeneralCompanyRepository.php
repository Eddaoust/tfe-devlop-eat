<?php

namespace App\Repository;

use App\Entity\GeneralCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GeneralCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeneralCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeneralCompany[]    findAll()
 * @method GeneralCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneralCompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeneralCompany::class);
    }

    /**
     * Get the count of all general companies
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCount()
    {
        return $this->createQueryBuilder('gen_comp')
            ->select('count(gen_comp.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return GeneralCompany[] Returns an array of GeneralCompany objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GeneralCompany
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
