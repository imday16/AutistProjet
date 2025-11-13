<?php

namespace App\Service;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReportRepository $reportRepository,
        private NotificationService $notificationService,
    ) {}

    /**
     * Signale un sujet
     */
    public function reportTopic(
        Topic $topic,
        User $reportedBy,
        string $reason,
        string $description = null
    ): Report {
        // Vérifier si l'utilisateur a déjà signalé ce sujet
        $existingReport = $this->reportRepository->findOneBy([
            'topic' => $topic,
            'reportedBy' => $reportedBy,
            'status' => 'pending',
        ]);

        if ($existingReport) {
            throw new \Exception('Vous avez déjà signalé ce sujet');
        }

        $report = new Report();
        $report->setTopic($topic);
        $report->setReportedBy($reportedBy);
        $report->setReason($reason);
        $report->setDescription($description);
        $report->setStatus('pending');
        $report->setCreatedAt(new \DateTime());
        $report->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($report);
        $this->entityManager->flush();

        // Notifier les admins
        $this->notificationService->notifyAdminNewReport($report);

        return $report;
    }

    /**
     * Signale un commentaire
     */
    public function reportComment(
        Comment $comment,
        User $reportedBy,
        string $reason,
        string $description = null
    ): Report {
        // Vérifier si l'utilisateur a déjà signalé ce commentaire
        $existingReport = $this->reportRepository->findOneBy([
            'comment' => $comment,
            'reportedBy' => $reportedBy,
            'status' => 'pending',
        ]);

        if ($existingReport) {
            throw new \Exception('Vous avez déjà signalé ce commentaire');
        }

        $report = new Report();
        $report->setComment($comment);
        $report->setReportedBy($reportedBy);
        $report->setReason($reason);
        $report->setDescription($description);
        $report->setStatus('pending');
        $report->setCreatedAt(new \DateTime());
        $report->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($report);
        $this->entityManager->flush();

        // Notifier les admins
        $this->notificationService->notifyAdminNewReport($report);

        return $report;
    }

    /**
     * Résout un signalement
     */
    public function resolveReport(Report $report, User $resolvedBy): void
    {
        $report->setStatus('resolved');
        $report->setResolvedBy($resolvedBy);
        $report->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Rejette un signalement
     */
    public function dismissReport(Report $report, User $resolvedBy): void
    {
        $report->setStatus('dismissed');
        $report->setResolvedBy($resolvedBy);
        $report->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * Récupère tous les signalements en attente
     */
    public function getPendingReports(): array
    {
        return $this->reportRepository->findBy(
            ['status' => 'pending'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère tous les signalements résolus
     */
    public function getResolvedReports(): array
    {
        return $this->reportRepository->findBy(
            ['status' => 'resolved'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère tous les signalements rejetés
     */
    public function getDismissedReports(): array
    {
        return $this->reportRepository->findBy(
            ['status' => 'dismissed'],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère les signalements d'un sujet
     */
    public function getTopicReports(Topic $topic): array
    {
        return $this->reportRepository->findBy(
            ['topic' => $topic],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère les signalements d'un commentaire
     */
    public function getCommentReports(Comment $comment): array
    {
        return $this->reportRepository->findBy(
            ['comment' => $comment],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère le nombre de signalements d'un sujet
     */
    public function getTopicReportCount(Topic $topic): int
    {
        return count($this->getTopicReports($topic));
    }

    /**
     * Récupère le nombre de signalements d'un commentaire
     */
    public function getCommentReportCount(Comment $comment): int
    {
        return count($this->getCommentReports($comment));
    }

    /**
     * Vérifie si un utilisateur a déjà signalé un sujet
     */
    public function hasUserReportedTopic(Topic $topic, User $user): bool
    {
        $report = $this->reportRepository->findOneBy([
            'topic' => $topic,
            'reportedBy' => $user,
            'status' => 'pending',
        ]);

        return $report !== null;
    }

    /**
     * Vérifie si un utilisateur a déjà signalé un commentaire
     */
    public function hasUserReportedComment(Comment $comment, User $user): bool
    {
        $report = $this->reportRepository->findOneBy([
            'comment' => $comment,
            'reportedBy' => $user,
            'status' => 'pending',
        ]);

        return $report !== null;
    }

    /**
     * Récupère les statistiques des signalements
     */
    public function getReportStats(): array
    {
        $pending = count($this->getPendingReports());
        $resolved = count($this->getResolvedReports());
        $dismissed = count($this->getDismissedReports());

        return [
            'pending' => $pending,
            'resolved' => $resolved,
            'dismissed' => $dismissed,
            'total' => $pending + $resolved + $dismissed,
        ];
    }
}
