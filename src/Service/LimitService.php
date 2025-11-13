<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TopicRepository;
use App\Repository\CommentRepository;

class LimitService
{
    private const MAX_TOPICS_PER_DAY = 3;
    private const MAX_COMMENTS_PER_DAY = null; // Pas de limite

    public function __construct(
        private TopicRepository $topicRepository,
        private CommentRepository $commentRepository,
    ) {}

    /**
     * Vérifie si l'utilisateur peut créer un sujet aujourd'hui
     */
    public function canCreateTopic(User $user): bool
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $tomorrow = (clone $today)->modify('+1 day');

        $topicsToday = $this->topicRepository->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.createdAt >= :start')
            ->andWhere('t.createdAt < :end')
            ->setParameter('user', $user)
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();

        return $topicsToday < self::MAX_TOPICS_PER_DAY;
    }

    /**
     * Récupère le nombre de sujets créés par l'utilisateur aujourd'hui
     */
    public function getTopicsCreatedToday(User $user): int
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $tomorrow = (clone $today)->modify('+1 day');

        return (int) $this->topicRepository->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.createdAt >= :start')
            ->andWhere('t.createdAt < :end')
            ->setParameter('user', $user)
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère le nombre de sujets restants pour aujourd'hui
     */
    public function getRemainingTopicsToday(User $user): int
    {
        $created = $this->getTopicsCreatedToday($user);
        return max(0, self::MAX_TOPICS_PER_DAY - $created);
    }

    /**
     * Vérifie si l'utilisateur peut créer un commentaire
     */
    public function canCreateComment(User $user): bool
    {
        // Pas de limite pour les commentaires
        return true;
    }

    /**
     * Récupère le nombre de commentaires créés par l'utilisateur aujourd'hui
     */
    public function getCommentsCreatedToday(User $user): int
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $tomorrow = (clone $today)->modify('+1 day');

        return (int) $this->commentRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->andWhere('c.createdAt >= :start')
            ->andWhere('c.createdAt < :end')
            ->setParameter('user', $user)
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère le temps avant de pouvoir créer un nouveau sujet
     */
    public function getTimeUntilNextTopic(User $user): ?\DateTime
    {
        if ($this->canCreateTopic($user)) {
            return null;
        }

        // Récupérer le sujet le plus ancien créé aujourd'hui
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $tomorrow = (clone $today)->modify('+1 day');

        $oldestTopic = $this->topicRepository->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.createdAt >= :start')
            ->andWhere('t.createdAt < :end')
            ->setParameter('user', $user)
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->orderBy('t.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$oldestTopic) {
            return null;
        }

        // Ajouter 24 heures au sujet le plus ancien
        $nextAvailable = (clone $oldestTopic->getCreatedAt())->modify('+24 hours');

        return $nextAvailable > new \DateTime() ? $nextAvailable : null;
    }

    /**
     * Récupère les statistiques de limite pour un utilisateur
     */
    public function getLimitStats(User $user): array
    {
        return [
            'canCreateTopic' => $this->canCreateTopic($user),
            'topicsCreatedToday' => $this->getTopicsCreatedToday($user),
            'remainingTopicsToday' => $this->getRemainingTopicsToday($user),
            'maxTopicsPerDay' => self::MAX_TOPICS_PER_DAY,
            'canCreateComment' => $this->canCreateComment($user),
            'commentsCreatedToday' => $this->getCommentsCreatedToday($user),
            'maxCommentsPerDay' => self::MAX_COMMENTS_PER_DAY,
            'timeUntilNextTopic' => $this->getTimeUntilNextTopic($user),
        ];
    }
}
