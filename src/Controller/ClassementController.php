<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;

class ClassementController extends AbstractController
{
    #[Route('/classement', name: 'app_classement')]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findByScore();

        return $this->render('classement/index.html.twig', [
            'controller_name' => 'ClassementController',
            'games' => $games,
        ]);
    }
}
