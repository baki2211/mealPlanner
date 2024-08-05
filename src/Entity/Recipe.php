<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $cookingTime = null;

    #[ORM\Column(length: 255)]
    private ?string $calories = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $author = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'recipes')]
    private Collection $category;

    /**
     * @var Collection<int, IngredientList>
     */
    #[ORM\ManyToMany(targetEntity: IngredientList::class, cascade: ["persist"])]
    #[ORM\JoinTable(name: "recipe_ingredients")]
    private Collection $ingredients;

    /**
     * @var Collection<int, PlannerRecipe>
     */
    #[ORM\OneToMany(targetEntity: PlannerRecipe::class, mappedBy: 'recipe')]
    private Collection $plannerRecipes;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCookingTime(): ?string
    {
        return $this->cookingTime;
    }

    public function setCookingTime(string $cookingTime): static
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getCalories(): ?string
    {
        return $this->calories;
    }

    public function setCalories(string $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->author ? $this->author->getName() : null;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, IngredientList>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(IngredientList $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
          // $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(IngredientList $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

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
            $plannerRecipe->setRecipe($this);
        }

        return $this;
    }

    public function removePlannerRecipe(PlannerRecipe $plannerRecipe): static
    {
        if ($this->plannerRecipes->removeElement($plannerRecipe)) {
            // set the owning side to null (unless already changed)
            if ($plannerRecipe->getRecipe() === $this) {
                $plannerRecipe->setRecipe(null);
            }
        }

        return $this;
    }
}
