<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassementController extends AbstractController
{
    #[Route('/classement', name: 'app_classement')]
    public function index(): Response
    {
        return $this->render('classement/index.html.twig', [
            'controller_name' => 'ClassementController',
        ]);
    }
}
