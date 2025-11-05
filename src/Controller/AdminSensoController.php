<?php

namespace App\Controller;

use App\Entity\AdminSenso;
use App\Form\AdminSensoType;
use App\Repository\AdminSensoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/senso')]
final class AdminSensoController extends AbstractController
{
    #[Route(name: 'app_admin_senso_index', methods: ['GET'])]
    public function index(AdminSensoRepository $adminSensoRepository): Response
    {
        return $this->render('admin_senso/index.html.twig', [
            'admin_sensos' => $adminSensoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_senso_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adminSenso = new AdminSenso();
        $form = $this->createForm(AdminSensoType::class, $adminSenso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adminSenso);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_senso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_senso/new.html.twig', [
            'admin_senso' => $adminSenso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_senso_show', methods: ['GET'])]
    public function show(AdminSenso $adminSenso): Response
    {
        return $this->render('admin_senso/show.html.twig', [
            'admin_senso' => $adminSenso,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_senso_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdminSenso $adminSenso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminSensoType::class, $adminSenso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_senso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_senso/edit.html.twig', [
            'admin_senso' => $adminSenso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_senso_delete', methods: ['POST'])]
    public function delete(Request $request, AdminSenso $adminSenso, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminSenso->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adminSenso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_senso_index', [], Response::HTTP_SEE_OTHER);
    }
}
