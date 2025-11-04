<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SensoController extends AbstractController
{
    #[Route('/senso', name: 'app_senso')]
    public function index(): Response
    {
        return $this->render('senso/index.html.twig', [
            'controller_name' => 'SensoController',
        ]);
    }
}
