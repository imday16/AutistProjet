<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\CommentVote;
use App\Entity\Topic;
use App\Entity\TopicVote;
use App\Entity\User;
use App\Enum\VoteType;
use App\Repository\CommentVoteRepository;
use App\Repository\TopicVoteRepository;
use Doctrine\ORM\EntityManagerInterface;


class VoteService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TopicVoteRepository $topicVoteRepository,
        private CommentVoteRepository $commentVoteRepository,
        private TopicService $topicService,
        private CommentService $commentService,
    ) {}

    /**
     * Vote sur un sujet (upvote ou downvote)
     */
    public function voteOnTopic(Topic $topic, User $user, string $voteType): void
    {
        // Vérifier si l'utilisateur a déjà voté
        $existingVote = $this->topicVoteRepository->findOneBy([
            'topic' => $topic,
            'user' => $user,
        ]);

        if ($existingVote) {
            if ($existingVote->getVoteType() === $voteType) {
                // Même vote : supprimer le vote
                $this->removeTopicVote($existingVote, $topic);
            } else {
                // Vote différent : changer le vote
                $this->changeTopicVote($existingVote, $topic, $voteType);
            }
        } else {
            // Nouveau vote
            $this->addTopicVote($topic, $user, $voteType);
        }
    }

    /**
     * Ajoute un vote sur un sujet
     */
    private function addTopicVote(Topic $topic, User $user, string $voteType): void
    {
        $vote = new TopicVote();
        $vote->setTopic($topic);
        $vote->setUser($user);
    // Convertir la string en enum
        $voteEnum = match($voteType) {
        'upvote' => VoteType::UPVOTE,
        'downvote' => VoteType::DOWNVOTE,
        default => throw new \InvalidArgumentException("VoteType invalide")
    };

        $vote->setVoteType($voteEnum);
        $vote->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($vote);

        if ($voteEnum === VoteType::UPVOTE) {
            $this->topicService->incrementUpvotes($topic);
        } else {
            $this->topicService->incrementDownvotes($topic);
        }

        $this->entityManager->flush();
    }

    /**
     * Supprime un vote sur un sujet
     */
    private function removeTopicVote(TopicVote $vote, Topic $topic): void
    {
        $voteType = $vote->getVoteType();

        $this->entityManager->remove($vote);

        if ($voteType === 'upvote') {
            $this->topicService->decrementUpvotes($topic);
        } else {
            $this->topicService->decrementDownvotes($topic);
        }

        $this->entityManager->flush();
    }

    /**
     * Change le vote sur un sujet
     */
    private function changeTopicVote(TopicVote $vote, Topic $topic, string $newVoteType): void
    {
    // Conversion string -> enum
    $newVoteEnum = match($newVoteType) {
        'upvote' => VoteType::UPVOTE,
        'downvote' => VoteType::DOWNVOTE,
        default => throw new \InvalidArgumentException("VoteType invalide")
    };

        $oldVoteEnum = $vote->getVoteType();

        $vote->setVoteType($newVoteEnum);
// Décrémenter l’ancien vote
        if ($oldVoteEnum === VoteType::UPVOTE) {
            $this->topicService->decrementUpvotes($topic);
        } else {
            $this->topicService->decrementDownvotes($topic);
        }
 // Incrémenter le nouveau vote
        if ($newVoteEnum === VoteType::UPVOTE) {
            $this->topicService->incrementUpvotes($topic);
        } else {
            $this->topicService->incrementDownvotes($topic);
        }

        $this->entityManager->flush();
    }

    /**
     * Vote sur un commentaire (upvote ou downvote)
     */
    public function voteOnComment(Comment $comment, User $user, string $voteType): void
    {
        // Vérifier si l'utilisateur a déjà voté
        $existingVote = $this->commentVoteRepository->findOneBy([
            'comment' => $comment,
            'user' => $user,
        ]);

        if ($existingVote) {
            if ($existingVote->getVoteType() === $voteType) {
                // Même vote : supprimer le vote
                $this->removeCommentVote($existingVote, $comment);
            } else {
                // Vote différent : changer le vote
                $this->changeCommentVote($existingVote, $comment, $voteType);
            }
        } else {
            // Nouveau vote
            $this->addCommentVote($comment, $user, $voteType);
        }
    }

    /**
     * Ajoute un vote sur un commentaire
     */
    private function addCommentVote(Comment $comment, User $user, string $voteType): void
    {
        $vote = new CommentVote();
        $vote->setComment($comment);
        $vote->setUser($user);
    // Conversion string -> enum
        $voteEnum = match($voteType) {
        'upvote' => VoteType::UPVOTE,
        'downvote' => VoteType::DOWNVOTE,
        default => throw new \InvalidArgumentException("VoteType invalide")
    };

        $vote->setVoteType($voteEnum);
        $vote->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($vote);

        if ($voteEnum === VoteType::UPVOTE) {
            $this->commentService->incrementUpvotes($comment);
        } else {
            $this->commentService->incrementDownvotes($comment);
        }

        $this->entityManager->flush();
    }

    /**
     * Supprime un vote sur un commentaire
     */
    private function removeCommentVote(CommentVote $vote, Comment $comment): void
    {
        $voteType = $vote->getVoteType();

        $this->entityManager->remove($vote);

        if ($voteType === 'upvote') {
            $this->commentService->decrementUpvotes($comment);
        } else {
            $this->commentService->decrementDownvotes($comment);
        }

        $this->entityManager->flush();
    }

    /**
     * Change le vote sur un commentaire
     */
    private function changeCommentVote(CommentVote $vote, Comment $comment, string $newVoteType): void
    {

            // Conversion string -> enum
        $newVoteEnum = match($newVoteType) {
        'upvote' => VoteType::UPVOTE,
        'downvote' => VoteType::DOWNVOTE,
        default => throw new \InvalidArgumentException("VoteType invalide")
        };

        $oldVoteEnum = $vote->getVoteType();
        $vote->setVoteType($newVoteEnum);
    // Décrémenter l’ancien vote
        if ($oldVoteEnum === VoteType::UPVOTE) {
            $this->commentService->decrementUpvotes($comment);
        } else {
            $this->commentService->decrementDownvotes($comment);
        }
    // Incrémenter le nouveau vote
        if ($newVoteEnum === VoteType::UPVOTE) {
            $this->commentService->incrementUpvotes($comment);
        } else {
            $this->commentService->incrementDownvotes($comment);
        }

        $this->entityManager->flush();
    }

    /**
     * Récupère le vote de l'utilisateur sur un sujet
     */
    public function getUserTopicVote(Topic $topic, User $user): ?TopicVote
    {
        return $this->topicVoteRepository->findOneBy([
            'topic' => $topic,
            'user' => $user,
        ]);
    }

    /**
     * Récupère le vote de l'utilisateur sur un commentaire
     */
    public function getUserCommentVote(Comment $comment, User $user): ?CommentVote
    {
        return $this->commentVoteRepository->findOneBy([
            'comment' => $comment,
            'user' => $user,
        ]);
    }
}
