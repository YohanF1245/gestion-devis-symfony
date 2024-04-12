<?php

namespace App\Controller;

use App\Entity\DressEstimate;
use App\Form\DressEstimateType;
use App\Repository\DressEstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dress/estimate')]
class DressEstimateController extends AbstractController
{
    #[Route('/', name: 'app_dress_estimate_index', methods: ['GET'])]
    public function index(DressEstimateRepository $dressEstimateRepository): Response
    {
        return $this->render('dress_estimate/index.html.twig', [
            'dress_estimates' => $dressEstimateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dress_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dressEstimate = new DressEstimate();
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dressEstimate);
            $entityManager->flush();

            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dress_estimate/new.html.twig', [
            'dress_estimate' => $dressEstimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dress_estimate_show', methods: ['GET'])]
    public function show(DressEstimate $dressEstimate): Response
    {
        return $this->render('dress_estimate/show.html.twig', [
            'dress_estimate' => $dressEstimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dress_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DressEstimate $dressEstimate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dress_estimate/edit.html.twig', [
            'dress_estimate' => $dressEstimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dress_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, DressEstimate $dressEstimate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dressEstimate->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($dressEstimate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
    }
}
