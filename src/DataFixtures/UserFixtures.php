<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'testtest'))
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles(['ROLE_USER'])
                ->setBirthdate($faker->dateTimeThisDecade)
                ->setCreated($faker->dateTimeThisMonth);

            $manager->persist($user);
        }
        $admin = new User();
        $admin->setEmail('ed@test.be')
            ->setPassword($this->encoder->encodePassword($user, 'testtest'))
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setRoles(['ROLE_ADMIN'])
            ->setBirthdate($faker->dateTimeThisDecade)
            ->setCreated($faker->dateTimeThisMonth);
        $manager->persist($admin);
        $manager->flush();
    }
}
