<?php

namespace App\Controller;

use App\Entity\FactureEmit;
use App\Form\FactureEmitType;
use App\Repository\BusinessRepository;
use App\Repository\ClientRepository;
use App\Repository\DressEstimateRepository;
use App\Repository\EstimatePerformanceLinkRepository;
use App\Repository\EstimateTabRepository;
use App\Repository\FactureEmitRepository;
use App\Repository\PerformanceRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Extension\LogoutUrlExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facture/emit')]
class FactureEmitController extends AbstractController
{
    #[Route('/', name: 'app_facture_emit_index', methods: ['GET'])]
    public function index(BusinessRepository $businessRepository, EstimateTabRepository $estimateTabRepository, DressEstimateRepository $dressEstimateRepository, FactureEmitRepository $factureEmitRepository): Response
    {   
        $userId = $this->getUser()->getId();
        $businessId = $businessRepository->findBy(
            ['user_id' => $userId],
        );
        $estimatesTabs = $estimateTabRepository->findBy([
            'business_id' => $businessId[0],
        ]);
        $data = [];
        $facture[0]= null;
        foreach ($estimatesTabs as $estimateTab) {
            if($estimateTab->getFactureId() !== null){
                 $facture = $factureEmitRepository->findBy([
                'id' => $estimateTab->getFactureId(),
            ]);
            $estimate = $dressEstimateRepository->findBy([
                'id' => $estimateTab->getEstimateId(),
            ]);
            $row = array(
                'id' => $facture[0]->getId(),
                'num' =>$estimate[0]->getEstimateNumber(),
                'total' =>$estimate[0]->getTotal(),
                'isPaid' => $facture[0]->isIsPaid(),
                'creationDate' => $facture[0]->getCreationDate(),
                'intitule' => $estimate[0]->getIntitule(),
            );
            $data[] = $row;
            }
           
        }
        return $this->render('facture_emit/index.html.twig', [
            //'facture_emits' => $factureEmitRepository->findAll(),
            'data' => $data,
            'facture_emit' => $facture[0],
        ]);
    }

    #[Route('/new', name: 'app_facture_emit_new', methods: ['GET', 'POST'])]
    public function new(EstimateTabRepository $estimateTabRepository, DressEstimateRepository $dressEstimateRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $userId = $this->getUser()->getId();
        $estimates = $dressEstimateRepository->findBy(
            ['user_id' => $userId],
        );
        $estimateList = [];
        foreach ($estimates as $estimate) {
            $estimateTab = $estimateTabRepository->findBy([
                'estimate_id' => $estimate->getId(),
            ]);
            if($estimateTab[0]->getFactureId() == null){
                $estimateList[] = $estimate;
            }
        }
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
            'estimates' => $estimateList,
            'facture_emit' => $factureEmit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_emit_show', methods: ['GET'])]
    public function show(ClientRepository $clientRepository,BusinessRepository $businessRepository,PerformanceRepository $performanceRepository,EstimatePerformanceLinkRepository $estimatePerformanceLink, DressEstimateRepository $dressEstimateRepository,EstimateTabRepository $estimateTabRepository, FactureEmit $factureEmit, PdfService $pdf): Response
    {
        $estimateTab=$estimateTabRepository->findBy([
            'facture_id' => $factureEmit->getId(),
        ]);
        $estimate = $dressEstimateRepository->findBy([
            'id' => $estimateTab[0]->getEstimateId(),
        ]);
        $performancesList = $estimatePerformanceLink->findBy(
            ['estimate_tab_id' => $estimateTab[0]->getId()]
        );
        $performances = [];
        foreach ($performancesList as $performance) {
            $perf =$performanceRepository->findBy([
                'id' => $performance->getPerformanceId(),
            ]);
            $performances[] = $perf[0];
        }
        $user = $this->getUser();
        $year = date_format($estimate[0]->getCreationDate(),"Y");
        $estimatenum = $estimate[0]->getEstimateNumber();

        
        $enstimatenumLen = strlen((string)$estimatenum);

        $addZero = '';
        
        switch ($enstimatenumLen) {
            case 1:
                $addZero = "00000";
                break;
            case 2:
                $addZero = "0000";
                # code...
                break;
            case 3:
                $addZero = "000";
                # code...
                break;
            case 4:
                $addZero = "00";
                # code...
                break;
            case 5:
                $addZero = "0";
                # code...
                break;   
            default:
                # code...
                break;
        }
        
        $invoiceNumber = "F-".$year."-".$addZero.$estimatenum;
        $businessId = $estimate[0]->getEstimateTab()->getBusinessId()->getId();
        $clientId = $estimate[0]->getClientId()->getId();
        $client = $clientRepository->findOneBy(
            ['id' => $clientId]
        );
        $business = $businessRepository->findOneBy(
            ['id' => $businessId]
        );
        $logoExt = pathinfo($business->getLogo(),PATHINFO_EXTENSION);
        $userId = $user->getId();
        $logoName = $userId.".".$logoExt;
        $logoExt = pathinfo($user->getSignature(), PATHINFO_EXTENSION);
        $signName = $userId.".".$logoExt;
        return $this->render('facture_emit/show.html.twig', [
            'facture_emit' => $factureEmit,
            'estimate_num' => $invoiceNumber,
            'logo_name' => $logoName,
            'sign_name' => $signName,
            'estimate' => $estimate[0],
            'performances' => $performances,
            'client' => $client,
            'business' => $business,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/pdf', name: 'invoice_pdf')]
    public function generateInvoicePdf(ClientRepository $clientRepository,BusinessRepository $businessRepository,PerformanceRepository $performanceRepository,EstimatePerformanceLinkRepository $estimatePerformanceLink, DressEstimateRepository $dressEstimateRepository,EstimateTabRepository $estimateTabRepository, FactureEmit $factureEmit, PdfService $pdf): Response{
        $user = $this->getUser();
        $estimateTab=$estimateTabRepository->findBy([
            'facture_id' => $factureEmit->getId(),
        ]);
        $estimate = $dressEstimateRepository->findBy([
            'id' => $estimateTab[0]->getEstimateId(),
        ]);
        $performancesList = $estimatePerformanceLink->findBy(
            ['estimate_tab_id' => $estimateTab[0]->getId()]
        );
        $performances = [];
        foreach ($performancesList as $performance) {
            $perf =$performanceRepository->findBy([
                'id' => $performance->getPerformanceId(),
            ]);
            $performances[] = $perf[0];
        }
        $user = $this->getUser();
        $year = date_format($estimate[0]->getCreationDate(),"Y");
        $estimatenum = $estimate[0]->getEstimateNumber();

        
        $enstimatenumLen = strlen((string)$estimatenum);

        $addZero = '';
        
        switch ($enstimatenumLen) {
            case 1:
                $addZero = "00000";
                break;
            case 2:
                $addZero = "0000";
                # code...
                break;
            case 3:
                $addZero = "000";
                # code...
                break;
            case 4:
                $addZero = "00";
                # code...
                break;
            case 5:
                $addZero = "0";
                # code...
                break;   
            default:
                # code...
                break;
        }
        
        $invoiceNumber = "F-".$year."-".$addZero.$estimatenum;
        $businessId = $estimate[0]->getEstimateTab()->getBusinessId()->getId();
        $clientId = $estimate[0]->getClientId()->getId();
        $client = $clientRepository->findOneBy(
            ['id' => $clientId]
        );
        $business = $businessRepository->findOneBy(
            ['id' => $businessId]
        );
        $logoExt = pathinfo($business->getLogo(),PATHINFO_EXTENSION);
        $userId = $user->getId();
        $logoName = $userId.".".$logoExt;
        $logoExt = pathinfo($user->getSignature(), PATHINFO_EXTENSION);
        $signName = $userId.".".$logoExt;

        $imageData = file_get_contents(dirname(__DIR__, 2)."/public/img/pigeon-devis-portrait.png");
        $extension = 'png';
        $base64 = base64_encode($imageData);
        // dd($base64);
        $logo64 = "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
        $logoExtension ="gif";
        if($business->getLogo() != null) {
            
            // dd($business->getLogo());
            $imageData = file_get_contents($business->getLogo());
            $extension = pathinfo($business->getLogo(), PATHINFO_EXTENSION);
            $base64 = base64_encode($imageData);
        }
        if($user->getSignature() != null) {
            $imageData = file_get_contents($user->getSignature());
            $logoExtension = pathinfo($user->getSignature(), PATHINFO_EXTENSION);
            $logo64 = base64_encode($imageData);
        }
        //dd('base 64' .$logo64.' ext : '.$logoExtension);
        $html = $this->render('facture_emit/pdf.html.twig', [
            'estimate_num' => $invoiceNumber,
            'image' => $base64,
            'extension' => $extension,
            'logo' => $logo64,
            'logoExt' => $logoExtension,
            'dress_estimate' => $estimate[0],
            'performances' => $performances,
            'client' => $client,
            'business' => $business,
            'user' => $user,
        ]);
        $pdf->showPdfFile($html);
        // return $this->render('facture_emit/pdf.html.twig', [
        //     'Content-Type' => 'application/pdf',
        // ]);
        
        
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
          ]);
    }
    #[Route('/select/estimate', name: 'app_facture_select_estimate', methods:['GET'])]
    public function selectEstimate(EstimateTabRepository $estimateTabRepository,DressEstimateRepository $dressEstimateRepository)
    {
        $userId = $this->getUser()->getId();
        $estimates = $dressEstimateRepository->findBy(
            ['user_id' => $userId],
        );
        $estimateList = [];
        foreach ($estimates as $estimate) {
            $estimateTab = $estimateTabRepository->findBy([
                'estimate_id' => $estimate->getId(),
            ]);
            if($estimateTab[0]->getFactureId() !== null){
                $estimateList[] = $estimate;
            }
        }
        return $this->render('facture_emit/select.estimate.html.twig', [
            'estimates' => $estimateList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_emit_edit', methods: ['GET', 'POST'])]
    public function edit(EstimateTabRepository $estimateTabRepository, DressEstimateRepository $dressEstimateRepository, Request $request, FactureEmit $factureEmit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureEmitType::class, $factureEmit);
        $form->handleRequest($request);
        $userId = $this->getUser()->getId();
        $estimates = $dressEstimateRepository->findBy(
            ['user_id' => $userId],
        );
        $estimateList = [];
        foreach ($estimates as $estimate) {
            $estimateTab = $estimateTabRepository->findBy([
                'estimate_id' => $estimate->getId(),
            ]);
            if($estimateTab[0]->getFactureId() == null){
                $estimateList[] = $estimate;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_emit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_emit/edit.html.twig', [
            'estimates' => $estimateList,
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
