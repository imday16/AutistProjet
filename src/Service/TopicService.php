<?php

namespace App\Service;

use App\Entity\Topic;
use App\Entity\User;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;

class TopicService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TopicRepository $topicRepository,
    ) {}

    /**
     * Récupère tous les sujets avec pagination
     */
    public function getAllTopics(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        $topics = $this->topicRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        $total = $this->topicRepository->count([]);

        return [
            'topics' => $topics,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit),
        ];
    }

    /**
     * Récupère un sujet par ID
     */
    public function getTopicById(int $id): ?Topic
    {
        return $this->topicRepository->find($id);
    }

    /**
     * Crée un nouveau sujet
     */
    public function createTopic(string $title, string $description, User $user): Topic
    {
        $topic = new Topic();
        $topic->setTitle($title);
        $topic->setDescription($description);
        $topic->setUser($user);
        $topic->setCreatedAt(new \DateTimeImmutable());
        $topic->setUpdatedAt(new \DateTimeImmutable());
        $topic->setUpvotes(0);
        $topic->setDownvotes(0);

        $this->entityManager->persist($topic);
        $this->entityManager->flush();

        return $topic;
    }

    /**
     * Met à jour un sujet
     */
    public function updateTopic(Topic $topic, string $title, string $description): Topic
    {
        $topic->setTitle($title);
        $topic->setDescription($description);
        $topic->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $topic;
    }

    /**
     * Supprime un sujet
     */
    public function deleteTopic(Topic $topic): void
    {
        $this->entityManager->remove($topic);
        $this->entityManager->flush();
    }

    /**
     * Incrémente les upvotes d'un sujet
     */
    public function incrementUpvotes(Topic $topic): void
    {
        $topic->setUpvotes($topic->getUpvotes() + 1);
        $this->entityManager->flush();
    }

    /**
     * Décrémente les upvotes d'un sujet
     */
    public function decrementUpvotes(Topic $topic): void
    {
        $topic->setUpvotes(max(0, $topic->getUpvotes() - 1));
        $this->entityManager->flush();
    }

    /**
     * Incrémente les downvotes d'un sujet
     */
    public function incrementDownvotes(Topic $topic): void
    {
        $topic->setDownvotes($topic->getDownvotes() + 1);
        $this->entityManager->flush();
    }

    /**
     * Décrémente les downvotes d'un sujet
     */
    public function decrementDownvotes(Topic $topic): void
    {
        $topic->setDownvotes(max(0, $topic->getDownvotes() - 1));
        $this->entityManager->flush();
    }

    /**
     * Récupère le nombre de commentaires d'un sujet
     */
    public function getCommentCount(Topic $topic): int
    {
        return count($topic->getComments());
    }
}
