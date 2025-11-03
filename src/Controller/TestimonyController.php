<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestimonyController extends AbstractController
{
    #[Route('/testimony', name: 'app_testimony')]
    public function index(): Response
    {
        return $this->render('testimony/index.html.twig', [
            'controller_name' => 'TestimonyController',
        ]);
    }
}
