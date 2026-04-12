<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\AgencyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgencyFixtures extends Fixture
{
    private const int AGENCIES = 2;

    public function load(ObjectManager $manager): void
    {
        AgencyFactory::createMany(self::AGENCIES);
        $manager->flush();

        foreach (AgencyFactory::all() as $agency) {
            $this->addReference('AGENCY'.$agency->getId(), $agency);
        }
    }
}
