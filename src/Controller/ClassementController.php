<?php

namespace App\Controller;

use App\Form\KeywordSearchType;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassementController extends AbstractController
{
    #[Route('/classement/{filter}', name: 'classement')]
    public function index(
        GameRepository $gameRepository,
        Request $request,
        string $filter,
    ): Response {
        $form = $this->createForm(KeywordSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            if (!empty($search)) {
                $games = $gameRepository->findLikeKeyword($search);
            } else {
                $games = $gameRepository->findByTime();
            }
        } else {
            $games = $gameRepository->findByTime();
            if ($filter === 'user') {
                $games = $gameRepository->findByUser();
            }
            if ($filter === 'classe') {
                $games = $gameRepository->findByClasse();
            }
            if ($filter === 'name') {
                $games = $gameRepository->findBy([], ['name' => 'ASC']);
            }
        }



        return $this->renderForm('classement/index.html.twig', [
            'controller_name' => 'ClassementController',
            'games' => $games,
            'form' => $form,
        ]);
    }
}
