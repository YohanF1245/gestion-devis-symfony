<?php

namespace App\Controller;

use App\Entity\FactureEmit;
use App\Form\FactureEmitType;
use App\Repository\DressEstimateRepository;
use App\Repository\FactureEmitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facture/emit')]
class FactureEmitController extends AbstractController
{
    #[Route('/', name: 'app_facture_emit_index', methods: ['GET'])]
    public function index(FactureEmitRepository $factureEmitRepository): Response
    {
        return $this->render('facture_emit/index.html.twig', [
            'facture_emits' => $factureEmitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_emit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factureEmit = new FactureEmit();
        $form = $this->createForm(FactureEmitType::class, $factureEmit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factureEmit);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_emit/new.html.twig', [
            'facture_emit' => $factureEmit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_emit_show', methods: ['GET'])]
    public function show(FactureEmit $factureEmit): Response
    {
        return $this->render('facture_emit/show.html.twig', [
            'facture_emit' => $factureEmit,
        ]);
    }
    #[Route('/select/estimate', name: 'app_facture_select_estimate', methods:['GET'])]
    public function selectEstimate(DressEstimateRepository $dressEstimateRepository)
    {
        $userId = $this->getUser()->getId();
        $estimates = $dressEstimateRepository->findBy(
            ['user_id' => $userId],
        );

        return $this->render('facture_emit/select.estimate.html.twig', [
            'estimates' => $estimates,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_emit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureEmit $factureEmit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureEmitType::class, $factureEmit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_emit/edit.html.twig', [
            'facture_emit' => $factureEmit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_emit_delete', methods: ['POST'])]
    public function delete(Request $request, FactureEmit $factureEmit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureEmit->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($factureEmit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
    }
}
