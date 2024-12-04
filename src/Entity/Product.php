<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Ajout de l'import pour les contraintes de validation

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide.")] // Validation pour s'assurer que le nom n'est pas vide
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom doit comporter au moins {{ limit }} caractères.", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")] // Validation de la longueur
    private string $nom;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide.")] // Validation pour s'assurer que le prix n'est pas vide
    #[Assert\Type(type: 'float', message: "Le prix doit être un nombre.")] // Validation pour s'assurer que le prix est un float
    #[Assert\GreaterThan(value: 0, message: "Le prix doit être supérieur à zéro.")] // Validation pour s'assurer que le prix est positif
    private float $prix;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[Assert\NotNull(message: "La catégorie ne peut pas être nulle.")] // Validation pour s'assurer que la catégorie est sélectionnée
    private ?Category $category = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)] 
    #[Assert\Length(max: 255, maxMessage: "La description ne peut pas dépasser {{ limit }} caractères.")] // Validation de la longueur de la description
    private ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
