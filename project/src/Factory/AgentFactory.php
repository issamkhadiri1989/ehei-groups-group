<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Agent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class AgentFactory extends PersistentObjectFactory
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
    }

    #[\Override]
    public static function class(): string
    {
        return Agent::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'agency' => AgencyFactory::random(),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'username' => self::faker()->userName(),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Agent $agent): void {
            $plainPassword = '123123';
            $agent->setPassword($this->hasher->hashPassword($agent, $plainPassword));
        });
    }
}
