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
    public function findAllWithRelation()
    {
        return $this->createQueryBuilder('project')
                    ->select('project', 'projectOwner', 'architect', 'generalCompany')
                    ->leftJoin('project.projectOwner', 'projectOwner')
                    ->leftJoin('project.architect', 'architect')
                    ->leftJoin('project.generalCompany', 'generalCompany')
                    ->where('project.deleted = false')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return mixed
     */
    public function getCompanyProjectName()
    {
        return $this->createQueryBuilder('project')
            ->select('project.name', 'projectOwner.id')
            ->leftJoin('project.projectOwner', 'projectOwner')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function getCompaniesGenCompanyName()
    {
        return $this->createQueryBuilder('project')
            ->select('project.name', 'generalCompany.id')
            ->leftJoin('project.generalCompany', 'generalCompany')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function getArchitectCompanyName()
    {
        return $this->createQueryBuilder('project')
            ->select('project.name', 'architect.id')
            ->leftJoin('project.architect', 'architect')
            ->getQuery()
            ->getResult();
    }

    public function getProjectCountByYears()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(p.id) as projectCount, YEAR(step.study) as year
            FROM project p
            INNER JOIN step ON p.steps_id = step.id
            GROUP BY YEAR(step.study)
            ORDER BY YEAR(step.study) ASC
        ';

        $doctrine = $conn->prepare($sql);
        $doctrine->execute();

        return $doctrine->fetchAll();
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getProjectTurnover()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT p.name, p.turnover, p.created
            FROM project p
            ORDER BY p.created DESC
            LIMIT 15
        ';

        $doctrine = $conn->prepare($sql);
        $doctrine->execute();

        return $doctrine->fetchAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getProjectStep($id)
    {
        return $this->createQueryBuilder('project')
                    ->select(
                        'project.lots',
                        'step.study',
                        'step.mastery',
                        'step.permitStart',
                        'step.permitEnd',
                        'step.worksStart',
                        'step.worksEnd',
                        'step.delivery'
                        )
                    ->leftJoin('project.steps', 'step')
                    ->andWhere('project.id = :projectId')
                    ->setParameter('projectId', $id)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getProjectState($id)
    {
        return $this->createQueryBuilder('project')
                    ->select(
                        'projectState.name',
                        'state.date',
                        'state.quantity'
                    )
                    ->leftJoin('project.state', 'state')
                    ->leftJoin('state.type', 'projectState')
                    ->andWhere('project.id = :projectId')
                    ->setParameter('projectId', $id)
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
