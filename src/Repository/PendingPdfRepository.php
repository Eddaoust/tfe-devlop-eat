<?php

namespace App\Repository;

use App\Entity\PendingPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PendingPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method PendingPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method PendingPdf[]    findAll()
 * @method PendingPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PendingPdfRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PendingPdf::class);
    }

    // /**
    //  * @return PendingPdf[] Returns an array of PendingPdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PendingPdf
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
