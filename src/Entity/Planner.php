<?php


namespace App\Entity;

use App\Repository\PlannerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlannerRepository::class)]
class Planner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'planner', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: PlannerRecipe::class, mappedBy: 'planner', cascade: ['persist', 'remove'])]
    private Collection $plannerRecipes;

    public function __construct()
    {
        $this->plannerRecipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
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
            $plannerRecipe->setPlanner($this);
        }

        return $this;
    }

    public function removePlannerRecipe(PlannerRecipe $plannerRecipe): static
    {
        if ($this->plannerRecipes->removeElement($plannerRecipe)) {
            // set the owning side to null (unless already changed)
            if ($plannerRecipe->getPlanner() === $this) {
                $plannerRecipe->setPlanner(null);
            }
        }

        return $this;
    }
}
