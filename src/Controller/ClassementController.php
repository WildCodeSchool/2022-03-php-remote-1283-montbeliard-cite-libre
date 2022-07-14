<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;

class ClassementController extends AbstractController
{
    #[Route('/classement/{filter}', name: 'classement')]
    public function index(GameRepository $gameRepository, string $filter): Response
    {
        $games = $gameRepository->findByScore();
        if ($filter === 'user') {
            $games = $gameRepository->findByUser();
        }
        if ($filter === 'classe') {
            $games = $gameRepository->findByClasse();
        }
        if ($filter === 'name') {
            $games = $gameRepository->findBy([], ['name' => 'ASC']);
        }
        return $this->render('classement/index.html.twig', [
            'controller_name' => 'ClassementController',
            'games' => $games,
        ]);
    }
}
