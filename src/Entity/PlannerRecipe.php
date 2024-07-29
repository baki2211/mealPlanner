<?php

namespace App\Entity;

use App\Repository\PlannerRecipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlannerRecipeRepository::class)]
class PlannerRecipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Planner::class, inversedBy: 'plannerRecipes')]
    private ?Planner $planner = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'plannerRecipes')]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(targetEntity: Week::class, inversedBy: 'plannerRecipes')]
    private ?Week $day = null;

    #[ORM\ManyToOne(targetEntity: Time::class, inversedBy: 'plannerRecipes')]
    private ?Time $time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanner(): ?Planner
    {
        return $this->planner;
    }

    public function setPlanner(?Planner $planner): static
    {
        $this->planner = $planner;
        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getDay(): ?Week
    {
        return $this->day;
    }

    public function setDay(?Week $day): static
    {
        $this->day = $day;
        return $this;
    }

    public function getTime(): ?Time
    {
        return $this->time;
    }

    public function setTime(?Time $time): static
    {
        $this->time = $time;
        return $this;
    }
}
