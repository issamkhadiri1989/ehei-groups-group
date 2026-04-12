<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\AccountFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    private const int ACCOUNTS = 10;

    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        AccountFactory::createMany(self::ACCOUNTS);
        $manager->flush();
    }
}
