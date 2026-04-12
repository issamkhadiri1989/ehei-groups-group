<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Agent>
 */
class AgentRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Agent) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAgentByAgency(string $username, string $agency): ?Agent
    {
        return $this->createQueryBuilder('a')
            ->join('a.agency', 'ag')
            ->where('a.username = :username')
            ->andwhere('ag.id = :agency')
            ->setParameter('username', $username)
            ->setParameter('agency', $agency)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUserByPinCode(?string $pinCode): ?Agent
    {
        if (null === $pinCode) {
            return null;
        }

        return $this->createQueryBuilder('a')
            ->where('a.pinCode = :pinCode')
            ->setParameter('pinCode', $pinCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
