<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\CustomerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    private const int CUSTOMERS = 10;

    public function getDependencies(): array
    {
        return [
            AgencyFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        CustomerFactory::createMany(self::CUSTOMERS);
        $manager->flush();
    }
}
