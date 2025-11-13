<?php

namespace App\Service;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\ForumModeration;
use App\Entity\CommentModeration;
use App\Service\NotificationService; // <-- manquant
use App\Repository\ForumModerationRepository;
use App\Repository\CommentModerationRepository;
use App\Repository\ReportRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;

class ModerationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ForumModerationRepository $forumModerationRepository,
        private CommentModerationRepository $commentModerationRepository,
        private ReportRepository $reportRepository,
        private TopicRepository $topicRepository,
        private NotificationService $notificationService,
    ) {}

    /**
     * Crée une modération pour un sujet (status: pending par défaut)
     */
    public function createTopicModeration(Topic $topic): ForumModeration
    {
        $moderation = new ForumModeration();
        $moderation->setTopic($topic);
        $moderation->setStatus('pending');
        $moderation->setCreatedAt(new \DateTime());
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($moderation);
        $this->entityManager->flush();

        return $moderation;
    }

    /**
     * Crée une modération pour un commentaire (status: pending par défaut)
     */
    public function createCommentModeration(Comment $comment): CommentModeration
    {
        $moderation = new CommentModeration();
        $moderation->setComment($comment);
        $moderation->setStatus('pending');
        $moderation->setCreatedAt(new \DateTime());
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($moderation);
        $this->entityManager->flush();

        return $moderation;
    }

    /**
     * Approuve un sujet
     */
    public function approveTopic(Topic $topic, User $moderator): void
    {
        $moderation = $this->forumModerationRepository->findOneBy(['topic' => $topic]);
        
        if (!$moderation) {
            $moderation = $this->createTopicModeration($topic);
        }

        $moderation->setStatus('approved');
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Rejette un sujet
     */
    public function rejectTopic(Topic $topic, User $moderator, string $reason): void
    {
        $moderation = $this->forumModerationRepository->findOneBy(['topic' => $topic]);
        
        if (!$moderation) {
            $moderation = $this->createTopicModeration($topic);
        }

        $moderation->setStatus('rejected');
        $moderation->setReason($reason);
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        // Notifier l'auteur du sujet
        $this->notificationService->notifyTopicRejected($topic, $reason);
    }

    /**
     * Signale un sujet comme abusif
     */
    public function flagTopic(Topic $topic, User $moderator, ?string $reason = null): void
    {
        $moderation = $this->forumModerationRepository->findOneBy(['topic' => $topic]);
        
        if (!$moderation) {
            $moderation = $this->createTopicModeration($topic);
        }

        $moderation->setStatus('flagged');
        $moderation->setReason($reason);
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Approuve un commentaire
     */
    public function approveComment(Comment $comment, User $moderator): void
    {
        $moderation = $this->commentModerationRepository->findOneBy(['comment' => $comment]);
        
        if (!$moderation) {
            $moderation = $this->createCommentModeration($comment);
        }

        $moderation->setStatus('approved');
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Rejette un commentaire
     */
    public function rejectComment(Comment $comment, User $moderator, string $reason): void
    {
        $moderation = $this->commentModerationRepository->findOneBy(['comment' => $comment]);
        
        if (!$moderation) {
            $moderation = $this->createCommentModeration($comment);
        }

        $moderation->setStatus('rejected');
        $moderation->setReason($reason);
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        // Notifier l'auteur du commentaire
        $this->notificationService->notifyCommentRejected($comment, $reason);
    }

    /**
     * Signale un commentaire comme abusif
     */
    public function flagComment(Comment $comment, User $moderator, ?string $reason = null): void

    {
        $moderation = $this->commentModerationRepository->findOneBy(['comment' => $comment]);
        
        if (!$moderation) {
            $moderation = $this->createCommentModeration($comment);
        }

        $moderation->setStatus('flagged');
        $moderation->setReason($reason);
        $moderation->setModeratedBy($moderator);
        $moderation->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Récupère la modération d'un sujet
     */
    public function getTopicModeration(Topic $topic): ?ForumModeration
    {
        return $this->forumModerationRepository->findOneBy(['topic' => $topic]);
    }

    /**
     * Récupère la modération d'un commentaire
     */
    public function getCommentModeration(Comment $comment): ?CommentModeration
    {
        return $this->commentModerationRepository->findOneBy(['comment' => $comment]);
    }

    /**
     * Récupère le statut d'un sujet
     */
    public function getTopicStatus(Topic $topic): string
    {
        $moderation = $this->getTopicModeration($topic);
        return $moderation ? $moderation->getStatus() : 'pending';
    }

    /**
     * Récupère le statut d'un commentaire
     */
    public function getCommentStatus(Comment $comment): string
    {
        $moderation = $this->getCommentModeration($comment);
        return $moderation ? $moderation->getStatus() : 'pending';
    }

    /**
     * Vérifie si un sujet est approuvé
     */
    public function isTopicApproved(Topic $topic): bool
    {
        return $this->getTopicStatus($topic) === 'approved';
    }

    /**
     * Vérifie si un commentaire est approuvé
     */
    public function isCommentApproved(Comment $comment): bool
    {
        return $this->getCommentStatus($comment) === 'approved';
    }

    /**
     * Récupère tous les sujets en attente de modération
     */
    public function getPendingTopics(): array
    {
        return $this->forumModerationRepository->findBy(
            ['status' => 'pending'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère tous les commentaires en attente de modération
     */
    public function getPendingComments(): array
    {
        return $this->commentModerationRepository->findBy(
            ['status' => 'pending'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère tous les sujets signalés
     */
    public function getFlaggedTopics(): array
    {
        return $this->forumModerationRepository->findBy(
            ['status' => 'flagged'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère tous les commentaires signalés
     */
    public function getFlaggedComments(): array
    {
        return $this->commentModerationRepository->findBy(
            ['status' => 'flagged'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère les statistiques de modération
     */
    public function getModerationStats(): array
    {
        $pendingTopics = count($this->getPendingTopics());
        $pendingComments = count($this->getPendingComments());
        $flaggedTopics = count($this->getFlaggedTopics());
        $flaggedComments = count($this->getFlaggedComments());
        $pendingReports = count($this->reportRepository->findBy(['status' => 'pending']));

        return [
            'pendingTopics' => $pendingTopics,
            'pendingComments' => $pendingComments,
            'flaggedTopics' => $flaggedTopics,
            'flaggedComments' => $flaggedComments,
            'pendingReports' => $pendingReports,
            'total' => $pendingTopics + $pendingComments + $flaggedTopics + $flaggedComments + $pendingReports,
        ];
    }
}
