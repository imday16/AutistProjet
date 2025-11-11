<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comments;

    /**
     * @var Collection<int, TopicVote>
     */
    #[ORM\OneToMany(targetEntity: TopicVote::class, mappedBy: 'user')]
    private Collection $topicVotes;

    /**
     * @var Collection<int, CommentVote>
     */
    #[ORM\OneToMany(targetEntity: CommentVote::class, mappedBy: 'user')]
    private Collection $commentVotes;

    /**
     * @var Collection<int, AdminTopicStatus>
     */
    #[ORM\OneToMany(targetEntity: AdminTopicStatus::class, mappedBy: 'moderatedBy')]
    private Collection $adminTopicStatuses;

    /**
     * @var Collection<int, CommentStatus>
     */
    #[ORM\OneToMany(targetEntity: CommentStatus::class, mappedBy: 'moderatedBy')]
    private Collection $commentStatuses;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'reportedBy')]
    private Collection $reports;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'resolvedBy')]
    private Collection $allreports;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->topicVotes = new ArrayCollection();
        $this->commentVotes = new ArrayCollection();
        $this->adminTopicStatuses = new ArrayCollection();
        $this->commentStatuses = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->allreports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
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
            $topicVote->setUser($this);
        }

        return $this;
    }

    public function removeTopicVote(TopicVote $topicVote): static
    {
        if ($this->topicVotes->removeElement($topicVote)) {
            // set the owning side to null (unless already changed)
            if ($topicVote->getUser() === $this) {
                $topicVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentVote>
     */
    public function getCommentVotes(): Collection
    {
        return $this->commentVotes;
    }

    public function addCommentVote(CommentVote $commentVote): static
    {
        if (!$this->commentVotes->contains($commentVote)) {
            $this->commentVotes->add($commentVote);
            $commentVote->setUser($this);
        }

        return $this;
    }

    public function removeCommentVote(CommentVote $commentVote): static
    {
        if ($this->commentVotes->removeElement($commentVote)) {
            // set the owning side to null (unless already changed)
            if ($commentVote->getUser() === $this) {
                $commentVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AdminTopicStatus>
     */
    public function getAdminTopicStatuses(): Collection
    {
        return $this->adminTopicStatuses;
    }

    public function addAdminTopicStatus(AdminTopicStatus $adminTopicStatus): static
    {
        if (!$this->adminTopicStatuses->contains($adminTopicStatus)) {
            $this->adminTopicStatuses->add($adminTopicStatus);
            $adminTopicStatus->setModeratedBy($this);
        }

        return $this;
    }

    public function removeAdminTopicStatus(AdminTopicStatus $adminTopicStatus): static
    {
        if ($this->adminTopicStatuses->removeElement($adminTopicStatus)) {
            // set the owning side to null (unless already changed)
            if ($adminTopicStatus->getModeratedBy() === $this) {
                $adminTopicStatus->setModeratedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentStatus>
     */
    public function getCommentStatuses(): Collection
    {
        return $this->commentStatuses;
    }

    public function addCommentStatus(CommentStatus $commentStatus): static
    {
        if (!$this->commentStatuses->contains($commentStatus)) {
            $this->commentStatuses->add($commentStatus);
            $commentStatus->setModeratedBy($this);
        }

        return $this;
    }

    public function removeCommentStatus(CommentStatus $commentStatus): static
    {
        if ($this->commentStatuses->removeElement($commentStatus)) {
            // set the owning side to null (unless already changed)
            if ($commentStatus->getModeratedBy() === $this) {
                $commentStatus->setModeratedBy(null);
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
            $report->setReportedBy($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getReportedBy() === $this) {
                $report->setReportedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getAllreports(): Collection
    {
        return $this->allreports;
    }

    public function addAllreport(Report $allreport): static
    {
        if (!$this->allreports->contains($allreport)) {
            $this->allreports->add($allreport);
            $allreport->setResolvedBy($this);
        }

        return $this;
    }

    public function removeAllreport(Report $allreport): static
    {
        if ($this->allreports->removeElement($allreport)) {
            // set the owning side to null (unless already changed)
            if ($allreport->getResolvedBy() === $this) {
                $allreport->setResolvedBy(null);
            }
        }

        return $this;
    }
}
