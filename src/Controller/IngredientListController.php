<?php

namespace App\Controller;

use App\Entity\IngredientList;
use App\Form\IngredientListType;
use App\Repository\IngredientListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/profile/ingredients')]
class IngredientListController extends AbstractController
{

    #[Route('/', name: 'app_ingredient_list_index', methods: ['GET'])]
    public function index(IngredientListRepository $ingredientListRepository): Response
    {
        $this->denyAccessIfBlocked();
        return $this->render('ingredient_list/index.html.twig', [
            'ingredient_lists' => $ingredientListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ingredient_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $ingredientList = new IngredientList();
        $form = $this->createForm(IngredientListType::class, $ingredientList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredientList);
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient_list/new.html.twig', [
            'ingredient_list' => $ingredientList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_list_show', methods: ['GET'])]
    public function show(IngredientList $ingredientList): Response
    {
        $this->denyAccessIfBlocked();
        return $this->render('ingredient_list/show.html.twig', [
            'ingredient_list' => $ingredientList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientList $ingredientList, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $form = $this->createForm(IngredientListType::class, $ingredientList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient_list/edit.html.twig', [
            'ingredient_list' => $ingredientList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_list_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientList $ingredientList, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        if ($this->isCsrfTokenValid('delete'.$ingredientList->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ingredientList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ingredient_list_index', [], Response::HTTP_SEE_OTHER);
    }
    private function denyAccessIfBlocked(): void
    {
        if ($this->isGranted('ROLE_BLOCKED')) {
            throw new AccessDeniedException('Access denied. Your account is blocked.');
        }
    }
}
