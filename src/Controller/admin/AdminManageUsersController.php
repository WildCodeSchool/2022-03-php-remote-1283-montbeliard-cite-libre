<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserType;
use App\Form\KeywordSearchType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/manage/users', name: 'manage_users_')]
class AdminManageUsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $form = $this->createForm(KeywordSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            if (!empty($search)) {
                $query = $userRepository->findLikeUsername($search);
            } else {
                $query = $userRepository->findByDescendingId();
            }
        } else {
            $query = $userRepository->findByDescendingId();
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->renderForm('admin/manage_users/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/manage_users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $password = $form->getData('password');
            $userRepository->add($user, true);

            return $this->redirectToRoute('manage_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/manage_users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('manage_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
