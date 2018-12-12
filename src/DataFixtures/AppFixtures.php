<?php

namespace App\DataFixtures;

use App\Entity\Architect;
use App\Entity\Company;
use App\Entity\CompanyCategory;
use App\Entity\Country;
use App\Entity\GeneralCompany;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $countryCode = ['LU', 'BE'];
        $countryName = ['Luxembourg', 'Belgique'];
        $companyAbb = [
            ['SARL', 'SA'],
            ['SA', 'SPRL', 'SPRL-S', 'SCRL', 'SCRI', 'SNC', 'SCS', 'SCA']
        ];
        $companyName = [
            ['Société à Responsabilité Limitée au Luxembourg', 'Société Anonyme'],
            ['Société anonyme', 'Société privée à responsabilité limitée', 'Société privée à responsabilité limitée « Starter »',
                'Société coopérative à responsabilité limitée', 'Société coopérative à responsabilité illimitée',
                'Société en nom collectif ', 'Société en commandite simple', 'Société en commandite par actions']
        ];

        for ($i = 0; $i <=1; $i++)
        {
            $country = new Country();
            $country->setCode($countryCode[$i])
                ->setName($countryName[$i]);
            $manager->persist($country);

            for ($j = 0; $j < count($companyAbb[$i]); $j++)
            {
                $companyCategory = new CompanyCategory();
                $companyCategory->setAbbreviation($companyAbb[$i][$j])
                    ->setName($companyName[$i][$j])
                    ->setCountry($country);
                $manager->persist($companyCategory);

                for ($k = 0; $k <= 5; $k++)
                {
                    $company = new Company();
                    $company->setCountry($country)
                        ->setCompanyCategory($companyCategory)
                        ->setName($faker->company)
                        ->setAddress($faker->streetAddress)
                        ->setPostalCode($faker->postcode)
                        ->setCity($faker->city)
                        ->setPhone($faker->phoneNumber)
                        ->setTvaNum($faker->creditCardNumber)
                        ->setRegistrationNum($faker->creditCardNumber)
                        ->setBank($faker->creditCardType)
                        ->setBankAccount($faker->iban('be'));
                    $manager->persist($company);

                    $architect = new Architect();
                    $architect->setName($faker->name)
                        ->setAddress($faker->streetAddress)
                        ->setPostalCode($faker->postcode)
                        ->setCity($faker->city)
                        ->setPhone($faker->phoneNumber)
                        ->setEmail($faker->email);
                    $manager->persist($architect);

                    $generalCompany = new GeneralCompany();
                    $generalCompany->setName($faker->company)
                        ->setAddress($faker->address)
                        ->setPostalCode($faker->postcode)
                        ->setCity($faker->city)
                        ->setPhone($faker->phoneNumber)
                        ->setEmail($faker->email);

                    $manager->persist($generalCompany);

                }
            }
        }

        $manager->flush();
    }
}
