<?php

namespace App\Controller;

use App\Entity\QuestionAsked;
use App\Repository\QuestionAskedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    #[Route('/summary', name: 'app_summary')]
    public function index(QuestionAskedRepository $qAskedRepository, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $qAsked = $qAskedRepository->findBy(['id' => $session->get("game")->getId()]);
        return $this->render('summary/index.html.twig', [
            'question_asked' => $qAsked,
        ]);
    }
}
