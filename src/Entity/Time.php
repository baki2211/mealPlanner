<?php

namespace App\Entity;

use App\Repository\TimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeRepository::class)]
class Time
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, PlannerRecipe>
     */
    #[ORM\OneToMany(mappedBy: 'time', targetEntity: PlannerRecipe::class)]
    private Collection $plannerRecipes;

    public function __construct()
    {
        $this->plannerRecipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, PlannerRecipe>
     */
    public function getPlannerRecipes(): Collection
    {
        return $this->plannerRecipes;
    }

    public function addPlannerRecipe(PlannerRecipe $plannerRecipe): static
    {
        if (!$this->plannerRecipes->contains($plannerRecipe)) {
            $this->plannerRecipes->add($plannerRecipe);
            $plannerRecipe->setTime($this);
        }

        return $this;
    }

    public function removePlannerRecipe(PlannerRecipe $plannerRecipe): static
    {
        if ($this->plannerRecipes->removeElement($plannerRecipe)) {
            // set the owning side to null (unless already changed)
            if ($plannerRecipe->getTime() === $this) {
                $plannerRecipe->setTime(null);
            }
        }

        return $this;
    }
}
