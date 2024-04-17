<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Outcome;
use App\Form\OutcomeType;
use App\Repository\BusinessRepository;
use App\Repository\OutcomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/outcome')]
class OutcomeController extends AbstractController
{
    #[Route('/', name: 'app_outcome_index', methods: ['GET'])]
    public function index(OutcomeRepository $outcomeRepository, BusinessRepository $businessRepository): Response
    {   
        $userId = $this->getUser()->getId();
        $business = $businessRepository->findOneBy(['user_id' => $userId]);
        $businessId = $business->getId();
        return $this->render('outcome/index.html.twig', [
            'outcomes' => $outcomeRepository->findBy(
                ['business_id' => $businessId]
            ),
        ]);
    }

    #[Route('/new', name: 'app_outcome_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, BusinessRepository $businessRepository): Response
    {
        $outcome = new Outcome();
        $form = $this->createForm(OutcomeType::class, $outcome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $business = $businessRepository->findOneBy(['user_id' => $userId]);
            //$businessId = $business->getId();
            //$businessId = $repo->find($userId);
            $outcome->setBusinessId($business);
            $entityManager->persist($outcome);
            $entityManager->flush();

            return $this->redirectToRoute('app_outcome_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outcome/new.html.twig', [
            'outcome' => $outcome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outcome_show', methods: ['GET'])]
    public function show(Outcome $outcome): Response
    {
        return $this->render('outcome/show.html.twig', [
            'outcome' => $outcome,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_outcome_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outcome $outcome, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutcomeType::class, $outcome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_outcome_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outcome/edit.html.twig', [
            'outcome' => $outcome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outcome_delete', methods: ['POST'])]
    public function delete(Request $request, Outcome $outcome, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outcome->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($outcome);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_outcome_index', [], Response::HTTP_SEE_OTHER);
    }
}
