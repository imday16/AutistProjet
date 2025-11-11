<?php

namespace App\Entity;

use App\Repository\ForumSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumSettingsRepository::class)]
class ForumSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $requiredApproval = null;

    #[ORM\Column]
    private ?bool $requiredCommentApproval = null;

    #[ORM\Column]
    private ?bool $allowReports = null;

    #[ORM\Column]
    private ?int $maxTopicsPerDay = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isRequiredApproval(): ?bool
    {
        return $this->requiredApproval;
    }

    public function setRequiredApproval(bool $requiredApproval): static
    {
        $this->requiredApproval = $requiredApproval;

        return $this;
    }

    public function isRequiredCommentApproval(): ?bool
    {
        return $this->requiredCommentApproval;
    }

    public function setRequiredCommentApproval(bool $requiredCommentApproval): static
    {
        $this->requiredCommentApproval = $requiredCommentApproval;

        return $this;
    }

    public function isAllowReports(): ?bool
    {
        return $this->allowReports;
    }

    public function setAllowReports(bool $allowReports): static
    {
        $this->allowReports = $allowReports;

        return $this;
    }

    public function getMaxTopicsPerDay(): ?int
    {
        return $this->maxTopicsPerDay;
    }

    public function setMaxTopicsPerDay(int $maxTopicsPerDay): static
    {
        $this->maxTopicsPerDay = $maxTopicsPerDay;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
