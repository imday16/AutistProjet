<?php

namespace App\Entity;

use App\Repository\AdminSensoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminSensoRepository::class)]
class AdminSenso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $explication = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $idee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getExplication(): ?string
    {
        return $this->explication;
    }

    public function setExplication(string $explication): static
    {
        $this->explication = $explication;

        return $this;
    }

    public function getIdee(): ?string
    {
        return $this->idee;
    }

    public function setIdee(string $idee): static
    {
        $this->idee = $idee;

        return $this;
    }
}
