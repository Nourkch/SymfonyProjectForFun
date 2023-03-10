<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestingRoutesController extends AbstractController
{
    #[Route('/testing/routes', name: 'app_testing_routes')] // annotation
    public function index(): Response
    {
        return $this->render('testing_routes/index.html.twig', [
            'controller_name' => 'TestingRoutesController',
        ]);
    }

    #[Route('/testing/routes/{name}', name: 'testing')]  // attributes
    public function name($name): Response
    {
        return $this->render('testing_routes/routing.html.twig', [
                    // key => value
                    'name' => $name
                ]);
    }

    
}
