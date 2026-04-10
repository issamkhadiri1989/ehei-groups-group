<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Account;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Account>
 */
final class AccountFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Account::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'balance' => self::faker()->randomFloat(nbMaxDecimals: 0, max: 1000000),
            'creationDate' => self::faker()->dateTimeThisYear(),
            'owner' => CustomerFactory::random(),
            'active' => self::faker()->boolean(),
            'number' => self::faker()->iban(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Account $account): void {})
        ;
    }
}
