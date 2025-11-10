<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Service\TopicService;
use App\Service\VoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ForumController extends AbstractController
{
    public function __construct(
        private TopicService $topicService,
        private VoteService $voteService,
    ) {}

    #[Route('/forum', name: 'app_forum', methods: ['GET'])]
    public function index(): Response
    {
        $data = $this->topicService->getAllTopics();
        
        return $this->render('forum/index.html.twig', [
            'topics' => $data['topics'],
        ]);
    }

    #[Route('/forum/topic/create', name: 'app_forum_create_topic', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createTopic(Request $request): Response
    {
        $title = $request->request->get('title');
        $description = $request->request->get('description');

        if (!$title || !$description) {
            $this->addFlash('error', 'Titre et description requis');
            return $this->redirectToRoute('app_forum');
        }

        $this->topicService->createTopic($title, $description, $this->getUser());
        $this->addFlash('success', 'Sujet créé avec succès');

        return $this->redirectToRoute('app_forum');
    }

    #[Route('/forum/topic/{id}/vote/{voteType}', name: 'app_forum_vote_topic', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function voteTopic(Topic $topic, string $voteType): Response
    {
        if ($voteType !== 'upvote' && $voteType !== 'downvote') {
            throw $this->createNotFoundException('Type de vote invalide');
        }

        $this->voteService->voteOnTopic($topic, $this->getUser(), $voteType);

        return $this->redirectToRoute('app_topic', ['id' => $topic->getId()]);
    }
}