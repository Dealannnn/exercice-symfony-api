<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Ajout de l'import pour les contraintes de validation

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom de la catégorie ne peut pas être vide.")] // Validation pour s'assurer que le nom n'est pas vide
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom doit comporter au moins {{ limit }} caractères.", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")] // Validation de la longueur
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }    

    public function setNom(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
