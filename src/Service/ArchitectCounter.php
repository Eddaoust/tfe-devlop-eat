<?php
/**
 * Created by PhpStorm.
 * User: edmonddaoust
 * Date: 2018-12-21
 * Time: 09:30
 */

namespace App\Service;


use App\Repository\ArchitectRepository;

class ArchitectCounter
{
    private $architectCount;

    /**
     * ArchitectCounter constructor.
     * @param ArchitectRepository $repo
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __construct(ArchitectRepository $repo)
    {
        $architect = $repo->getCount();
        $this->architectCount = $architect;
    }

    /**
     * @return int
     */
    public function getArchitectsCount()
    {
        return $this->architectCount;
    }
}