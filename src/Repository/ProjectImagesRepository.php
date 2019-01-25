<?php

namespace App\Repository;

use App\Entity\ProjectImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProjectImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectImages[]    findAll()
 * @method ProjectImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectImagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProjectImages::class);
    }

    // /**
    //  * @return ProjectImages[] Returns an array of ProjectImages objects
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
    public function findOneBySomeField($value): ?ProjectImages
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
