<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Service\CommentService;
use App\Service\VoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TopicController extends AbstractController
{
    public function __construct(
        private CommentService $commentService,
        private VoteService $voteService,
    ) {}

    #[Route('/forum/topic/{id}', name: 'app_topic', methods: ['GET'])]
    public function index(Topic $topic): Response
    {
        $userTopicVote = null;
        if ($this->getUser()) {
            $userTopicVote = $this->voteService->getUserTopicVote($topic, $this->getUser());
        }

        return $this->render('topic/index.html.twig', [
            'topic' => $topic,
            'userTopicVote' => $userTopicVote,
        ]);
    }

    #[Route('/forum/topic/{id}/comment/create', name: 'app_forum_create_comment', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createComment(Topic $topic, Request $request): Response
    {
        $content = $request->request->get('content');

        if (!$content) {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide');
            return $this->redirectToRoute('app_topic', ['id' => $topic->getId()]);
        }

        $this->commentService->createComment($topic, $this->getUser(), $content);
        $this->addFlash('success', 'Commentaire ajouté avec succès');

        return $this->redirectToRoute('app_topic', ['id' => $topic->getId()]);
    }

    #[Route('/forum/comment/{id}/vote/{voteType}', name: 'app_forum_vote_comment', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function voteComment(Comment $comment, string $voteType): Response
    {
        if ($voteType !== 'upvote' && $voteType !== 'downvote') {
            throw $this->createNotFoundException('Type de vote invalide');
        }

        $this->voteService->voteOnComment($comment, $this->getUser(), $voteType);

        return $this->redirectToRoute('app_topic', ['id' => $comment->getTopic()->getId()]);
    }
}