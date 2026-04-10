<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer', name: 'app_customer')]
final class CustomerController extends AbstractController
{
    #[Route('/{id}/show', name: '__show')]
    public function index(Customer $customer): Response
    {
        return $this->render('customer/index.html.twig', [
            'customer' => $customer,
        ]);
    }
}
