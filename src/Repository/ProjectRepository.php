<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }


    /**
     * Get the count of all projects
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCount()
    {
        return $this->createQueryBuilder('project')
                    ->select('count(project.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    /**
     * Resolve the N+1 issue
     * Get the data project with link in minimum request
     * @return mixed
     */
    public function findAllWithLink()
    {
        return $this->createQueryBuilder('project')
                    ->select('project', 'projectOwner', 'architect', 'generalCompany')
                    ->leftJoin('project.projectOwner', 'projectOwner')
                    ->leftJoin('project.architect', 'architect')
                    ->leftJoin('project.generalCompany', 'generalCompany')
                    ->getQuery()
                    ->getResult();
    }
    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
