<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customers', name: 'app_customers')]
final class CustomerController extends AbstractController
{
    #[Route('/manage', name: '__manage')]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [
        ]);
    }
}
