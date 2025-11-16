<?php 

namespace App\Controller;

use App\Entity\Topic;
use Symfony\Component\HttpFoundation\Request;
use App\Service\LimitService;
use App\Service\ReportService;
use App\Service\ModerationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminForumController extends AbstractController{

    public function __construct(
        private ModerationService $moderationService,
        private ReportService $reportService,
        private LimitService $limitService,
    ){}

    #[Route('/admin/forum/dashboard', name: 'admin_forum_dashboard')]
    public function dashboard(): Response
    {
        $stats = $this->moderationService->getModerationStats();
        $reportStats = $this->reportService->getReportStats();

        return $this->render('admin/forum/dashboard.html.twig',[
            'moderationStats'=> $stats,
            'reportStats'=> $reportStats,
        ]);
    }

    #[Route('/admin/forum/topics/pending', name: 'admin_forum_pending_topics')]
    public function pendingTopics(): Response 
    {
        $topics = $this->moderationService->getPendingTopics();

        return $this->render('admin/forum/pending_topics.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/admin/forum/topic/{id}/approve', name: 'admin_forum_approve_topic',
    methods: ['POST'])]
    public function approveTopic(Topic $topic): Response 
    {

        $this->moderationService->approveTopic($topic, $this->getUser());
        $this->addFlash('success', 'Sujet approuvé');

        return $this->redirectToRoute('admin_forum_pending_topics');
    }

    #[Route('/admin/forum/topic/{id}/reject', name: 'admin_forum_reject_topic',
    methods: ['POST'])]
    public function rejectTopic(Topic $topic, Request $request): Response
    {
        $reason = $request->request->get('reason');
        $this->moderationService->rejectTopic($topic, $this->getUser(), $reason);
        $this->addFlash('success', 'Sujet rejeté');

        return $this->redirectToRoute('admin_forum_pending_topics');
    }

    #[Route('/admin/forum/reports', name: 'admin_forum_reports')]
    public function reports(): Response
    {
        $reports = $this->reportService->getPendingReports();

        return $this->render('admin/forum/reports.html.twig', [
            'reports' => $reports,
        ]);
    }
}