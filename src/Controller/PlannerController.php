<?php
namespace App\Controller;
use App\Entity\Planner;
use App\Entity\PlannerRecipe;
use App\Entity\Time;
use App\Entity\User;
use App\Entity\Week;
use App\Form\PlannerType;
use App\Repository\PlannerRecipeRepository;
use App\Repository\PlannerRepository;
use App\Repository\RecipeRepository;
use App\Repository\TimeRepository;
use App\Repository\UserRepository;
use App\Repository\WeekRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
#[Route('/profile/planner')]
class PlannerController extends AbstractController
{
    #[Route('/', name: 'app_planner', methods: ['GET'])]
    public function index(
        PlannerRepository $plannerRepository,
        UserRepository $userRepository,
        RecipeRepository $recipeRepository,
        WeekRepository $weekRepository,
        TimeRepository $timeRepository
    ): Response {
        $this->denyAccessIfBlocked();
        $user = $this->getUser();
        $days = $weekRepository->findAll();
        $times = $timeRepository->findAll();
        $planner = $plannerRepository->findOneBy(['user' => $user]);
        $recipesInPlanner = [];
        if ($planner) {
            foreach ($planner->getPlannerRecipes() as $plannerRecipe) {
                $recipesInPlanner[$plannerRecipe->getDay()->getId()][$plannerRecipe->getTime()->getId()][] = $plannerRecipe;
            }
        }
        return $this->render('planner/index.html.twig', [
            'days' => $days,
            'times' => $times,
            'recipesInPlanner' => $recipesInPlanner,
        ]);
    }
    #[Route('/planner/add-recipe', name: 'app_planner_add_recipe')]
    public function addRecipe(
        Request $request,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $dayId = $request->query->get('day');
        $timeId = $request->query->get('time');
        $plannerId = $request->query->get('planner');
        $recipes = $recipeRepository->findAll();
        if ($request->isMethod('POST')) {
            $recipeId = $request->request->get('recipe');
            $recipe = $recipeRepository->find($recipeId);
            $planner = $entityManager->getRepository(Planner::class)->find($plannerId);
            $day = $entityManager->getRepository(Week::class)->find($dayId);
            $time = $entityManager->getRepository(Time::class)->find($timeId);
            $plannerRecipe = new PlannerRecipe();
            $plannerRecipe->setPlanner($planner);
            $plannerRecipe->setRecipe($recipe);
            $plannerRecipe->setDay($day);
            $plannerRecipe->setTime($time);
            $entityManager->persist($plannerRecipe);
            $entityManager->flush();
            return $this->redirectToRoute('app_planner');
        }
        return $this->render('planner/add_recipe.html.twig', [
            'recipes' => $recipes,
            'dayId' => $dayId,
            'timeId' => $timeId,
            'plannerId' => $plannerId,
        ]);
    }
    #[Route('/planner/remove-recipe/{id}', name: 'app_planner_remove_recipe', methods: ['POST'])]
    public function removeRecipe(
        $id,
        PlannerRecipeRepository $plannerRecipeRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $plannerRecipe = $plannerRecipeRepository->find($id);
        if ($plannerRecipe && $this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $entityManager->remove($plannerRecipe);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_planner');
    }
    private function denyAccessIfBlocked(): void
    {
        if ($this->isGranted('ROLE_BLOCK')) {
            throw new AccessDeniedException('Access denied. Your account is blocked.');
        }
    }
}