<?php
/**
 * Created by PhpStorm.
 * User: edmonddaoust
 * Date: 2018-12-14
 * Time: 22:00
 */

namespace App\Service;


use App\Repository\CompanyRepository;

class CompaniesCounter
{
    private $companiesCount;

    /**
     * CompaniesCounter constructor.
     * @param CompanyRepository $repo
     * Get the count of the companies register in database
     */
    public function __construct(CompanyRepository $repo)
    {
        $companies = $repo->getCount();
        $this->companiesCount = $companies;
    }

    /**
     * @return int
     */
    public function getCompaniesCount()
    {
        return $this->companiesCount;
    }
}