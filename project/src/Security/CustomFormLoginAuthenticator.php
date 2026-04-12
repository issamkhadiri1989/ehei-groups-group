<?php

namespace App\Security;

use App\Entity\Agent;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

final class CustomFormLoginAuthenticator extends AbstractAuthenticator
{
    private const string LOGIN_ROUTE = 'app_home';

    private const string SUCCESS_ROUTE = 'app_dashboard';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->isMethod(Request::METHOD_POST)
            && self::LOGIN_ROUTE === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        // Get all parameters sent via the LoginType
        $payload = $request->getPayload()->all('login');

        // Create an instance of Passport holding the info about the user logging in
        return new Passport(
            userBadge: new UserBadge(
                $payload['username'] ?? '',
                // this user loader loads the Agent (user) by username AND agency
                function (string $username) use ($payload): ?UserInterface {
                    /** @var AgentRepository $repository */
                    $repository = $this->entityManager->getRepository(Agent::class);

                    return $repository->findAgentByAgency(
                        username: $username,
                        agency: $payload['agency'] ?? '',
                    );
                }),
            credentials: new PasswordCredentials($payload['password'] ?? ''),
            badges: [
                new CsrfTokenBadge('_login_csrf_token', $payload['_token'] ?? ''),
            ],
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // this method is called on authentication success
        // generate the URL to the dashboard dynamically according to the name of the route
        $successTarget = $this->urlGenerator->generate(
            name: self::SUCCESS_ROUTE,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        // redirect the user to the dashboard
        return new RedirectResponse($successTarget);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $failureTarget = $this->urlGenerator->generate(
            name: self::LOGIN_ROUTE,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        // set the AuthenticationException in the session then display the error in the twig tempalte
        $request->getSession()->set(
            SecurityRequestAttributes::AUTHENTICATION_ERROR,
            $exception,
        );

        // redirect the user to the same page again to retry
        return new RedirectResponse($failureTarget);
    }
}
