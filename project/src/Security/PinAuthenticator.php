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
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

/**
 * This authenticator well be called when the user sends the PIN code via a **dummy** 2FA form.
 * Ths user should submit a POST request with the pin code as **pin_code** parameter.
 * the pin code should be saved in the database and it must be **UNIQUE**.
 */
final class PinAuthenticator extends AbstractAuthenticator
{
    private const string TWO_FA_ROUTE = 'app_security_2fa';
    private const string SUCCESS_ROUTE = 'app_dashboard';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // this authenticator should only be used when the request is POST
        // the user sends a `pin_code` parameter
        // the target route is `app_security_2fa`
        // to simulate this authenticator go to 2FAController
        return $request->isMethod('POST')
            && self::TWO_FA_ROUTE === $request->attributes->get('_route')
            && $request->request->has('pin_code');
    }

    public function authenticate(Request $request): Passport
    {
        $pinCode = $request->request->get('pin_code');
        $csrfToken = $request->request->get('_csrf_token');

        if (empty($pinCode)) {
            throw new CustomUserMessageAuthenticationException('Invalid pin code');
        }

        // sing the SelfValidatingPassport allows you to authenticate without the need for a password.
        $passport = new SelfValidatingPassport(
            userBadge: new UserBadge(
                userIdentifier: $pinCode,
                userLoader: function (string $code): ?UserInterface {
                    /** @var AgentRepository $repository */
                    $repository = $this->entityManager->getRepository(Agent::class);

                    return $repository->loadUserByPinCode($code);
                }
            ),
            badges: [
                new CsrfTokenBadge('__2fa_token__', $csrfToken),
            ],
        );

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $url = $this->urlGenerator->generate(name: self::SUCCESS_ROUTE, referenceType: UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);

        $url = $this->urlGenerator->generate(
            name: self::TWO_FA_ROUTE,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new RedirectResponse($url);
    }
}
