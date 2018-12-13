<?php
/**
 * Created by PhpStorm.
 * User: edmonddaoust
 * Date: 2018-12-13
 * Time: 12:44
 */

namespace App\Service;


use App\Repository\UserRepository;

class UsersCounter
{
    private $usersCount;

    /**
     * Get the count of the users register in database
     * UsersCounter constructor.
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        $users = $repo->findAll();
        $this->usersCount = count($users);
    }

    /**
     * Return the count accessible like a global var
     * @return int
     */
    public function getUsersCount()
    {
        return $this->usersCount;
    }
}