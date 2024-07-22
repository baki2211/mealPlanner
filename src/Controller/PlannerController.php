<?php

namespace App\Controller;

use App\Entity\Planner;
use App\Entity\User;
use App\Form\PlannerType;
use App\Repository\PlannerRepository;
use App\Repository\RecipeRepository;
use App\Repository\TimeRepository;
use App\Repository\UserRepository;
use App\Repository\WeekRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/profile/planner')]
class PlannerController extends AbstractController
{
    #[Route('/', name: 'app_planner', methods: ['GET'])]
    public function index(PlannerRepository $plannerRepository, UserRepository $userRepository, RecipeRepository $recipeRepository, WeekRepository $weekRepository, TimeRepository $timeRepository): Response
    {
        $this->denyAccessIfBlocked();
        $user = $this->getUser();
        $days = $weekRepository->findAll();
        $times = $timeRepository->findAll();
        $planner = $plannerRepository->findOneBy(['user' => $user]);
        $recipesInPlanner = [];
        if ($planner) {
            foreach ($planner->getPlannerRecipes() as $plannerRecipe) {
                $recipesInPlanner[$plannerRecipe->getDay()->getId()][$plannerRecipe->getTime()->getId()][] = $plannerRecipe->getRecipe();
            }
        }
        return $this->render('planner/index.html.twig', [
            'days' => $days,
            'times' => $times,
            'recipesInPlanner' => $recipesInPlanner,
        ]);
    }

    #[Route('/new', name: 'app_planner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $planner = new Planner();
        $form = $this->createForm(PlannerType::class, $planner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException('You must be logged in to create a recipe.');
            }
            $planner->setUser($user);
            $entityManager->persist($planner);
            $entityManager->flush();

            return $this->redirectToRoute('app_planner', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planner/new.html.twig', [
            'planner' => $planner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planner_show', methods: ['GET'])]
    public function show(Planner $planner): Response
    {
        $this->denyAccessIfBlocked();
        return $this->render('planner/show.html.twig', [
            'planner' => $planner,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planner_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planner $planner, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        $form = $this->createForm(PlannerType::class, $planner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planner/edit.html.twig', [
            'planner' => $planner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planner_delete', methods: ['POST'])]
    public function delete(Request $request, Planner $planner, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessIfBlocked();
        if ($this->isCsrfTokenValid('delete'.$planner->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planner_index', [], Response::HTTP_SEE_OTHER);
    }
    private function denyAccessIfBlocked(): void
    {
        if ($this->isGranted('ROLE_BLOCK')) {
            throw new AccessDeniedException('Access denied. Your account is blocked.');
        }
    }
}
