<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, options: ["default" => 0])]
    private int $upvotes = 0;

    #[ORM\Column(type: Types::INTEGER, options: ["default" => 0])]
    private int $downvotes = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'topic', cascade: ['persist', 'remove'])]
    private Collection $comments;

    /**
     * @var Collection<int, TopicVote>
     */
    #[ORM\OneToMany(targetEntity: TopicVote::class, mappedBy: 'topic')]
    private Collection $topicVotes;

    /**
     * @var Collection<int, ForumModeration>
     */
    #[ORM\OneToMany(targetEntity: ForumModeration::class, mappedBy: 'topic')]
    private Collection $ForumModerationes;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'topic')]
    private Collection $reports;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->topicVotes = new ArrayCollection();
        $this->ForumModerationes = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
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

    public function getUpvotes(): int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): static
    {
        $this->upvotes = $upvotes;
        return $this;
    }

    public function getDownvotes(): int
    {
        return $this->downvotes;
    }

    public function setDownvotes(int $downvotes): static
    {
        $this->downvotes = $downvotes;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
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
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTopic($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getTopic() === $this) {
                $comment->setTopic(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, TopicVote>
     */
    public function getTopicVotes(): Collection
    {
        return $this->topicVotes;
    }

    public function addTopicVote(TopicVote $topicVote): static
    {
        if (!$this->topicVotes->contains($topicVote)) {
            $this->topicVotes->add($topicVote);
            $topicVote->setTopic($this);
        }

        return $this;
    }

    public function removeTopicVote(TopicVote $topicVote): static
    {
        if ($this->topicVotes->removeElement($topicVote)) {
            // set the owning side to null (unless already changed)
            if ($topicVote->getTopic() === $this) {
                $topicVote->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ForumModeration>
     */
    public function getForumModerationes(): Collection
    {
        return $this->ForumModerationes;
    }

    public function addForumModeration(ForumModeration $ForumModeration): static
    {
        if (!$this->ForumModerationes->contains($ForumModeration)) {
            $this->ForumModerationes->add($ForumModeration);
            $ForumModeration->setTopic($this);
        }

        return $this;
    }

    public function removeForumModeration(ForumModeration $ForumModeration): static
    {
        if ($this->ForumModerationes->removeElement($ForumModeration)) {
            // set the owning side to null (unless already changed)
            if ($ForumModeration->getTopic() === $this) {
                $ForumModeration->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setTopic($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getTopic() === $this) {
                $report->setTopic(null);
            }
        }

        return $this;
    }
}
