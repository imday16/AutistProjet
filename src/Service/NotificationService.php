<?php

namespace App\Service;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Entity\Report;
use App\Entity\User;

class NotificationService
{
    /**
     * Notifie l'auteur que son sujet a été rejeté
     */
    public function notifyTopicRejected(Topic $topic, string $reason): void
    {
        $author = $topic->getUser();
        
        // TODO: Implémenter l'envoi de notification
        // Options:
        // 1. Email
        // 2. Notification in-app (créer une entité Notification)
        // 3. Message système
        
        // Exemple avec email:
        // $this->mailer->send(
        //     (new Email())
        //         ->to($author->getEmail())
        //         ->subject('Votre sujet a été rejeté')
        //         ->html("Votre sujet '{$topic->getTitle()}' a été rejeté. Raison: {$reason}")
        // );

        // Ou créer une notification in-app:
        // $notification = new Notification();
        // $notification->setUser($author);
        // $notification->setType('topic_rejected');
        // $notification->setMessage("Votre sujet '{$topic->getTitle()}' a été rejeté. Raison: {$reason}");
        // $this->entityManager->persist($notification);
        // $this->entityManager->flush();
    }

    /**
     * Notifie l'auteur que son commentaire a été rejeté
     */
    public function notifyCommentRejected(Comment $comment, string $reason): void
    {
        $author = $comment->getUser();
        
        // TODO: Implémenter l'envoi de notification
        // Voir notifyTopicRejected() pour les options
    }

    /**
     * Notifie les admins d'un nouveau signalement
     */
    public function notifyAdminNewReport(Report $report): void
    {
        // TODO: Implémenter l'envoi de notification aux admins
        // Options:
        // 1. Email aux admins
        // 2. Notification in-app
        // 3. Slack/Discord webhook
        
        // Exemple:
        // $admins = $this->userRepository->findByRole('ROLE_ADMIN');
        // foreach ($admins as $admin) {
        //     $this->mailer->send(
        //         (new Email())
        //             ->to($admin->getEmail())
        //             ->subject('Nouveau signalement')
        //             ->html("Un nouveau signalement a été créé: {$report->getReason()}")
        //     );
        // }
    }

    /**
     * Notifie l'auteur que son sujet a été approuvé
     */
    public function notifyTopicApproved(Topic $topic): void
    {
        $author = $topic->getUser();
        
        // TODO: Implémenter l'envoi de notification
    }

    /**
     * Notifie l'auteur que son commentaire a été approuvé
     */
    public function notifyCommentApproved(Comment $comment): void
    {
        $author = $comment->getUser();
        
        // TODO: Implémenter l'envoi de notification
    }

    /**
     * Notifie l'auteur d'un sujet qu'il a un nouveau commentaire
     */
    public function notifyNewComment(Comment $comment): void
    {
        $topicAuthor = $comment->getTopic()->getUser();
        
        // TODO: Implémenter l'envoi de notification
    }

    /**
     * Notifie l'auteur d'un commentaire qu'il a une réponse
     */
    public function notifyCommentReply(Comment $reply, Comment $parentComment): void
    {
        $parentAuthor = $parentComment->getUser();
        
        // TODO: Implémenter l'envoi de notification
    }
}
