<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\AgentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AgentFixtures extends Fixture implements DependentFixtureInterface
{
    private const int AGENTS = 10;

    public function load(ObjectManager $manager): void
    {
        AgentFactory::createMany(self::AGENTS);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AgencyFixtures::class,
        ];
    }
}
