<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('ed@daoust.com')
            ->setPassword($this->encoder->encodePassword($user, 'testtest'))
            ->setFirstName('Edmond')
            ->setLastName('Daoust')
            ->setBirthdate(new \DateTime('08/07/1987'))
            ->setCreated(new \DateTime('now'));

        $manager->persist($user);
        $manager->flush();
    }
}
