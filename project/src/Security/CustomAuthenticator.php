<?php

namespace App\Security;

use App\Entity\Agent;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
final class CustomAuthenticator extends AbstractAuthenticator
{
    private const string LOGIN_CHECK = 'app_home';
    private const string SUCCESS_LOGIN_TARGET = 'app_dashboard';
    private const string FAILED_LOGIN_TARGET = 'app_home';

    public function __construct(
        private EntityManagerInterface $manager,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->isMethod('POST') &&
            $request->attributes->get('_route') === self::LOGIN_CHECK;
    }

    public function authenticate(Request $request): Passport
    {
        $payload = $request->getPayload()->all('login');

        $username = $payload['username'];
        $password = $payload['password'];
        $agency = $payload['agency'];
        $csrfToken = $payload["_csrf_token"];

        /** @var AgentRepository $repository */
        $repository = $this->manager->getRepository(Agent::class);

        $passport = new Passport(
            userBadge: new UserBadge($username, fn(string $identifier) => $repository->findAgentByAgency($identifier, $agency)),
            credentials: new PasswordCredentials($password),
            badges: [
                new CsrfTokenBadge(csrfTokenId: 'connect', csrfToken: $csrfToken),
            ],
        );

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return new RedirectResponse($this->urlGenerator->generate(self::SUCCESS_LOGIN_TARGET));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->urlGenerator->generate(self::FAILED_LOGIN_TARGET));
    }
}
