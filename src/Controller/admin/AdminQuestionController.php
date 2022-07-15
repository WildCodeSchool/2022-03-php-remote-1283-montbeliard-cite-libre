<?php

namespace App\Controller\admin;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Form\KeywordSearchType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/question', name: 'question_')]
class AdminQuestionController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $form = $this->createForm(KeywordSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            if (!empty($search)) {
                $query = $questionRepository->findLikeQuestion($search);
            } else {
                $query = $questionRepository->findByDescendingId();
            }
        } else {
            $query = $questionRepository->findByDescendingId();
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->renderForm('admin/question/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $answer = new Answer();
        $question->addAnswer($answer);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionRepository->add($question, true);

            $this->addFlash('success', 'La nouvelle question a bien été créée');

            return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('admin/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Question $question,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ): Response {
        $form = $this->createForm(QuestionType::class, $question);
        $originalAnswers = new ArrayCollection();

        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalAnswers as $answer) {
                if (false === $question->getAnswers()->contains($answer)) {
                    $answerRepository->remove($answer, true);
                }
            }
            $questionRepository->add($question, true);

            $this->addFlash('success', 'La question a bien été modifiée');

            return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $questionRepository->remove($question, true);
        }
        $this->addFlash('success', 'La question a bien été supprimée');

        return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
    }
}
