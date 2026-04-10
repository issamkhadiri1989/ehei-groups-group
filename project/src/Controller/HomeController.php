<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);

        return $this->render('home/index.html.twig', [
            'authentication_error' => $authenticationError,
            'username' => $username,
            'form' => $form->createView(),
        ]);
    }
}
