<?php

namespace App\DataFixtures;

use App\Entity\Architect;
use App\Entity\Company;
use App\Entity\CompanyCategory;
use App\Entity\Country;
use App\Entity\GeneralCompany;
use App\Entity\Project;
use App\Entity\Shareholder;
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
            ['SARL', 'SA', 'PP'],
            ['SA', 'SPRL', 'SPRL-S', 'SCRL', 'SCRI', 'SNC', 'SCS', 'SCA', 'PP']
        ];
        $companyName = [
            ['Société à Responsabilité Limitée au Luxembourg', 'Société Anonyme', 'Personne Physique'],
            ['Société anonyme', 'Société privée à responsabilité limitée', 'Société privée à responsabilité limitée « Starter »',
                'Société coopérative à responsabilité limitée', 'Société coopérative à responsabilité illimitée',
                'Société en nom collectif ', 'Société en commandite simple', 'Société en commandite par actions', 'Personne Physique']
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

                    for ($l = 0; $l <= 1; $l++)
                    {
                        $shareholder = new Shareholder();
                        $shareholder->setCompany($company)
                                    ->setPart($faker->numberBetween(0, 100));
                        $manager->persist($shareholder);

                        $project = new Project();
                        $project->setProjectOwner($company)
                                ->setName($faker->domainWord)
                                ->setAddress($faker->streetAddress)
                                ->setPostalCode($faker->postcode)
                                ->setCity($faker->city)
                                ->setDescription($faker->paragraph(2))
                                ->setPointOfInterest($faker->paragraph(2))
                                ->setFieldSize($faker->numberBetween(1, 10000))
                                ->setTurnover($faker->numberBetween(200000, 10000000))
                                ->setLots($faker->numberBetween(5, 50))
                                ->setCreated($faker->dateTimeThisDecade)
                                ->setArchitect($architect)
                                ->setGeneralCompany($generalCompany);
                        $manager->persist($project);
                    }

                }
            }
        }

        $manager->flush();
    }
}
