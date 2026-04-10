<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Customer;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Customer>
 */
final class CustomerFactory extends PersistentObjectFactory
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
        return Customer::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'agency' => AgencyFactory::random(),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'phone' => self::faker()->phoneNumber(),
            'city' => self::faker()->city(),
            'address' => self::faker()->address(),
            'zipCode' => self::faker()->postcode(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Customer $customer): void {})
        ;
    }
}
