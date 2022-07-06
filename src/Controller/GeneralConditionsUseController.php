<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralConditionsUseController extends AbstractController
{
    #[Route('/general/conditions/use', name: 'app_general_conditions_use')]
    public function index(): Response
    {
        return $this->render('general_conditions_use/index.html.twig', [
            'controller_name' => 'GeneralConditionsUseController',
        ]);
    }
}
