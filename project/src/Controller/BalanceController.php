<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/balance', name: 'app_balance')]
#[IsGranted('ROLE_USER')]
final class BalanceController extends AbstractController
{
    #[Route('/', name: '__index')]
    public function index(): Response
    {
        return $this->render('balance/index.html.twig', [
            'controller_name' => 'BalanceController',
        ]);
    }
}
