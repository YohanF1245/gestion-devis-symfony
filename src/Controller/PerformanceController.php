<?php

namespace App\Controller;

use App\Entity\Performance;
use App\Form\PerformanceType;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/performance')]
class PerformanceController extends AbstractController
{
    #[Route('/', name: 'app_performance_index', methods: ['GET'])]
    public function index(PerformanceRepository $performanceRepository): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('performance/index.html.twig', [
            'performances' => $performanceRepository->findBy(
                ['user_id' => $userId]
            ),
        ]);
    }

    #[Route('/new', name: 'app_performance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $performance = new Performance();
        $form = $this->createForm(PerformanceType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser();
            $performance->setUserId($userId);
            $entityManager->persist($performance);
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance/new.html.twig', [
            'performance' => $performance,
            'form' => $form,
        ]);
    }

    #[Route('/new/modal', name: 'app_performance_new_modal', methods: ['GET', 'POST'])]
    public function new_modal(RequestStack $requestStack, EntityManagerInterface $entityManager): Response
    {
        $request = $requestStack->getMainRequest();
        $performance = new Performance();
        //$form = $this->createForm(PerformanceType::class, $performance);
        $form = $this->createForm(PerformanceType::class, $performance, [
            'action' => $this->generateUrl('app_dress_estimate_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser();
            $performance->setUserId($userId);
            $entityManager->persist($performance);
            $entityManager->flush();
            $response = $this->redirectToRoute('app_dress_estimate_new', []);
            //return $this->redirectToRoute('app_performance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance/new.html.twig', [
            'performance' => $performance,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_performance_show', methods: ['GET'])]
    public function show(Performance $performance): Response
    {
        return $this->render('performance/show.html.twig', [
            'performance' => $performance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_performance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Performance $performance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PerformanceType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance/edit.html.twig', [
            'performance' => $performance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_performance_delete', methods: ['POST'])]
    public function delete(Request $request, Performance $performance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$performance->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($performance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_performance_index', [], Response::HTTP_SEE_OTHER);
    }
}
