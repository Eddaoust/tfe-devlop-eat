<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * Get the count of all companies
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCount()
    {
        return $this->createQueryBuilder('company')
            ->select('count(company.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getCompanyByCountry()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(c.id) AS count, country.name, country.code
            FROM company c 
            LEFT JOIN country ON c.country_id = country.id
            GROUP BY country.id
        ';

        $doctrine = $conn->prepare($sql);
        $doctrine->execute();

        return $doctrine->fetchAll();
    }


    // /**
    //  * @return Company[] Returns an array of Company objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
