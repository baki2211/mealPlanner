<?php

namespace App\Controller;

use App\Entity\IngredientList;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/profile/recipe')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessIfBlocked();
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException('You must be logged in to create a recipe.');
            }
            $recipe->setAuthor($user);

            // Check for existing ingredients
            foreach ($recipe->getIngredients() as $ingredient) {
                $existingIngredient = $entityManager->getRepository(IngredientList::class)->findOneBy(['name' => $ingredient->getName()]);
                if ($existingIngredient) {
                    // Replace with existing ingredient
                    $recipe->removeIngredient($ingredient);
                    $recipe->addIngredient($existingIngredient);
                } else {
                    $entityManager->persist($ingredient);
                }
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        $this->denyAccessIfBlocked();
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->get('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/myrecipes', name: 'app_my_recipes', methods: ['GET'])]
    public function filterByUser(RecipeRepository $recipeRepository, UserRepository $userRepository): Response
    {
        // Get the currently logged-in user
        $user = $this->$userRepository->getUser();

        // Check if the user is authenticated
        if (!$user) {
            throw $this->createAccessDeniedException('You are not logged in.');
        }

        // Get recipes for the current user
        $recipes = $user->getRecipes();

        return $this->render('recipe/personal_recipes.html.twig', [
            'recipes' => $recipes,
        ]);
    }
    private function denyAccessIfBlocked(): void
    {
        if ($this->isGranted('ROLE_BLOCKED')) {
            throw new AccessDeniedException('Access denied. Your account is blocked.');
        }
    }
}
