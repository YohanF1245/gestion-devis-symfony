<?php

namespace App\Controller;

use App\Entity\EstimatePerformanceLink;
use App\Form\EstimatePerformanceLinkType;
use App\Repository\EstimatePerformanceLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/estimate/performance/link')]
class EstimatePerformanceLinkController extends AbstractController
{
    #[Route('/', name: 'app_estimate_performance_link_index', methods: ['GET'])]
    public function index(EstimatePerformanceLinkRepository $estimatePerformanceLinkRepository): Response
    {
        return $this->render('estimate_performance_link/index.html.twig', [
            'estimate_performance_links' => $estimatePerformanceLinkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_estimate_performance_link_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $estimatePerformanceLink = new EstimatePerformanceLink();
        $form = $this->createForm(EstimatePerformanceLinkType::class, $estimatePerformanceLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($estimatePerformanceLink);
            $entityManager->flush();

            return $this->redirectToRoute('app_estimate_performance_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('estimate_performance_link/new.html.twig', [
            'estimate_performance_link' => $estimatePerformanceLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_performance_link_show', methods: ['GET'])]
    public function show(EstimatePerformanceLink $estimatePerformanceLink): Response
    {
        return $this->render('estimate_performance_link/show.html.twig', [
            'estimate_performance_link' => $estimatePerformanceLink,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_estimate_performance_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EstimatePerformanceLink $estimatePerformanceLink, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EstimatePerformanceLinkType::class, $estimatePerformanceLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_estimate_performance_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('estimate_performance_link/edit.html.twig', [
            'estimate_performance_link' => $estimatePerformanceLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_performance_link_delete', methods: ['POST'])]
    public function delete(Request $request, EstimatePerformanceLink $estimatePerformanceLink, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimatePerformanceLink->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($estimatePerformanceLink);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_estimate_performance_link_index', [], Response::HTTP_SEE_OTHER);
    }
}
