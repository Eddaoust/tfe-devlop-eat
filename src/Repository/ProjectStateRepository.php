<?php

namespace App\Repository;

use App\Entity\ProjectState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProjectState|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectState|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectState[]    findAll()
 * @method ProjectState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectStateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProjectState::class);
    }

    // /**
    //  * @return ProjectState[] Returns an array of ProjectState objects
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
    public function findOneBySomeField($value): ?ProjectState
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
