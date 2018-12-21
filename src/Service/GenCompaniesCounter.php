<?php
/**
 * Created by PhpStorm.
 * User: edmonddaoust
 * Date: 2018-12-21
 * Time: 10:25
 */

namespace App\Service;


use App\Repository\GeneralCompanyRepository;

class GenCompaniesCounter
{
    private $genCompanyCount;

    /**
     * ArchitectCounter constructor.
     * @param GeneralCompanyRepository $repo
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __construct(GeneralCompanyRepository $repo)
    {
        $genCompany = $repo->getCount();
        $this->genCompanyCount = $genCompany;
    }

    /**
     * @return int
     */
    public function getGenCompaniesCount()
    {
        return $this->genCompanyCount;
    }
}