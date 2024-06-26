<?php

namespace App\Controller;

use App\Entity\FactureEmit;
use App\Form\FactureEmitType;
use App\Repository\DressEstimateRepository;
use App\Repository\EstimateTabRepository;
use App\Repository\FactureEmitRepository;
use Doctrine\ORM\EntityManager;
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
    {   ;
        $factures = $factureEmitRepository->findAll();
        return $this->render('facture_emit/index.html.twig', [
            'facture_emits' => $factureEmitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_emit_new', methods: ['GET', 'POST'])]
    public function new(EstimateTabRepository $estimateTabRepository, DressEstimateRepository $dressEstimateRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $estimates = $dressEstimateRepository->findBy(
            ['user_id' => $userId],
        );
        $factureEmit = new FactureEmit();
        $form = $this->createForm(FactureEmitType::class, $factureEmit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimateId = $request->get('estimateSelect');
            $estimate = $dressEstimateRepository->findBy([
                'id' => $estimateId,
            ]);
            $estimateTab = $estimateTabRepository->findBy([
                'estimate_id' => $estimateId,
            ]);
            //$factureEmit->setEstimateTab($estimateTab[0]);
            if($factureEmit->getMajoration()=== null){
                $factureEmit->setMajoration(0);
            };
            $entityManager->persist($factureEmit);
            $estimateTab[0]->setFactureId($factureEmit);
            $entityManager->persist($estimateTab[0]);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_emit/new.html.twig', [
            'estimates' => $estimates,
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
    public function delete(Request $request,FactureEmitRepository $factureEmitRepository, FactureEmit $factureEmit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureEmit->getId(), $request->getPayload()->get('_token'))) {
            $estimateTab =$factureEmit->getEstimateTab();
            $query = $entityManager->createQuery(
                'UPDATE App\Entity\EstimateTab e
                SET e.facture_id = NULL
                WHERE e.facture_id = :id'
            )->setParameter('id', $factureEmit->getid(), 'uuid');
            $query->execute();         

            
            $queryBuilder = $factureEmitRepository->createQueryBuilder('f');
            $queryBuilder->delete()
                        ->where('f.id = :id')
                        ->setParameter('id', $factureEmit->getId(), 'uuid');
            $query  = $queryBuilder->getQuery();
            $result  = $query->getResult();
            // $entityManager->remove($factureEmit);
            // $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
    }
}
