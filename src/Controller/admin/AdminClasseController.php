<?php

namespace App\Controller\admin;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Form\KeywordSearchType;
use App\Repository\ClasseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/classe')]
class AdminClasseController extends AbstractController
{
    #[Route('/', name: 'classe_index', methods: ['GET', 'POST'])]
    public function index(
        ClasseRepository $classeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $form = $this->createForm(KeywordSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            if (!empty($search)) {
                $query = $classeRepository->findLikeclasse($search);
            } else {
                $query = $classeRepository->findAll();
            }
        } else {
            $query = $classeRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->renderForm('admin/classe/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'classe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClasseRepository $classeRepository): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classeRepository->add($classe, true);

            return $this->redirectToRoute('classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'classe_show', methods: ['GET'])]
    public function show(Classe $classe): Response
    {
        return $this->render('admin/classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }

    #[Route('/{id}/edit', name: 'classe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, ClasseRepository $classeRepository): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classeRepository->add($classe, true);

            return $this->redirectToRoute('classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'classe_delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, ClasseRepository $classeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $classe->getId(), $request->request->get('_token'))) {
            $classeRepository->remove($classe, true);
        }

        return $this->redirectToRoute('classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
