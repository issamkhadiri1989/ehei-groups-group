<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class TwoFactorController extends AbstractController
{
    #[Route('/authenticate', name: 'app_security_2fa')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $authenticationError = $authenticationUtils->getLastAuthenticationError();

        return $this->render('two_factor/index.html.twig', [
            'authentication_error' => $authenticationError,
        ]);
    }
}
