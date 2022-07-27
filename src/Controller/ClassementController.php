<?php

namespace App\Controller;

use App\Form\KeywordSearchType;
use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassementController extends AbstractController
{
    #[Route('/classement', name: 'classement')]
    public function index(
        GameRepository $gameRepository,
        Request $request,
        PaginatorInterface $paginator
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
        }

        $pagination = $paginator->paginate(
            $games, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->renderForm('classement/index.html.twig', [
            'form' => $form,
            'pagination' => $pagination,
        ]);
    }
}
