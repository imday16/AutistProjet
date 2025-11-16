<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Entity\User;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommentRepository $commentRepository,
         private ModerationService $moderationService,  // 👉 Injection du service
    ) {}

    /**
     * Récupère tous les commentaires d'un sujet
     */
    public function getCommentsByTopic(Topic $topic): array
    {
        return $this->commentRepository->findBy(
            ['topic' => $topic],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Récupère un commentaire par ID
     */
    public function getCommentById(int $id): ?Comment
    {
        return $this->commentRepository->find($id);
    }

    /**
     * Crée un nouveau commentaire
     */
    public function createComment(Topic $topic, User $user, string $content): Comment
    {
        $comment = new Comment();
        $comment->setTopic($topic);
        $comment->setUser($user);
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $comment->setUpvotes(0);
        $comment->setDownvotes(0);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        /**
         *  Créer la modération
         */

        $this->moderationService->createCommentModeration($comment);

        return $comment;
    }

    /**
     * Met à jour un commentaire
     */
    public function updateComment(Comment $comment, string $content): Comment
    {
        $comment->setContent($content);
        $comment->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $comment;
    }

    /**
     * Supprime un commentaire
     */
    public function deleteComment(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    /**
     * Incrémente les upvotes d'un commentaire
     */
    public function incrementUpvotes(Comment $comment): void
    {
        $comment->setUpvotes($comment->getUpvotes() + 1);
        $this->entityManager->flush();
    }

    /**
     * Décrémente les upvotes d'un commentaire
     */
    public function decrementUpvotes(Comment $comment): void
    {
        $comment->setUpvotes(max(0, $comment->getUpvotes() - 1));
        $this->entityManager->flush();
    }

    /**
     * Incrémente les downvotes d'un commentaire
     */
    public function incrementDownvotes(Comment $comment): void
    {
        $comment->setDownvotes($comment->getDownvotes() + 1);
        $this->entityManager->flush();
    }

    /**
     * Décrémente les downvotes d'un commentaire
     */
    public function decrementDownvotes(Comment $comment): void
    {
        $comment->setDownvotes(max(0, $comment->getDownvotes() - 1));
        $this->entityManager->flush();
    }
}
