<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\ForumModerationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumModerationRepository::class)]
class ForumModeration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ForumModerations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Topic $topic = null;

    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reason = null;

    #[ORM\ManyToOne(inversedBy: 'ForumModerations')]
    private ?User $moderatedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getModeratedBy(): ?User
    {
        return $this->moderatedBy;
    }

    public function setModeratedBy(?User $moderatedBy): static
    {
        $this->moderatedBy = $moderatedBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    } 
}
