<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'rule_')]
class GameRuleController extends AbstractController
{
    #[Route('rule', name: 'index')]
    public function index(): Response
    {
        return $this->render('game_rule/index.html.twig', [
            'controller_name' => 'GameRuleController',
        ]);
    }

    #[Route('/animation', name: 'animation')]
    public function animation(): Response
    {
        return $this->render('confetties/index.html.twig');
    }
}
