<?php

namespace App\DataFixtures;

use App\Entity\ProjectState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProjectStateFixtures extends Fixture
{
	public function load (ObjectManager $manager)
	{
		$vente = new ProjectState();
		$vente->setName('Vente')
			->setCode('SALE');
		$prevente = new ProjectState();
		$prevente->setName('Prevente')
			->setCode('PRES');
		$option = new ProjectState();
		$option->setName('Option')
			->setCode('OPTI');
		$location = new ProjectState();
		$location->setName('Location')
			->setCode('LOCA');
		$projection = new ProjectState();
		$projection->setName('Projection')
			->setCode('PROJ');
		$manager->persist($vente);
		$manager->persist($prevente);
		$manager->persist($option);
		$manager->persist($location);
		$manager->persist($projection);

		$manager->flush();
	}
}
