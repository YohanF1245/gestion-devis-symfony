<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\DressEstimate;
use App\Entity\EstimatePerformanceLink;
use App\Entity\EstimateTab;
use App\Form\DressEstimateType;
use App\Repository\BusinessRepository;
use App\Repository\ClientRepository;
use App\Repository\DressEstimateRepository;
use App\Repository\EstimatePerformanceLinkRepository;
use App\Repository\EstimateTabPerformanceRepository;
use App\Repository\EstimateTabRepository;
use App\Repository\PerformanceRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Config\TwigConfig;


#[Route('/dress/estimate')]
class DressEstimateController extends AbstractController
{
    #[Route('/', name: 'app_dress_estimate_index', methods: ['GET'])]
    public function index(DressEstimateRepository $dressEstimateRepository): Response
    {
        $estimate = $dressEstimateRepository->findBy(
            ['user_id' => $this->getUser()],
        );
        return $this->render('dress_estimate/index.html.twig', [
            //'dress_estimates' => $dressEstimateRepository->findAll(),
            'dress_estimates' => $estimate,
        ]);
    }
    #[Route('/count', name:'app_dress_estimate_count', methods:['GET', 'POST'])]
    public function count(Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManagerInterface)
    {
        
        
    }
    #[Route('/new', name: 'app_dress_estimate_new', methods: ['GET', 'POST'])]
    public function new(BusinessRepository $businessRepository, EstimateTabRepository $estimateTabRepository, Request $request, ClientRepository $clientRepository, PerformanceRepository $performanceRepository,DressEstimateRepository $dressEstimateRepository, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $businessId = $businessRepository->findBy(
            ['user_id' => $userId],
        );
        //dd($businessId[0]->getId()->__toString());
        $year = date('Y');
        $start =$year."-01-01";
        $end =$year."-12-31";
        $queryDateSelect = $estimateTabRepository->createQueryBuilder('et')
        ->join('et.estimate_id', 'd')
        ->join('et.business_id', 'b')
        ->where('d.creation_date BETWEEN :start AND :end')
        ->setParameter('start', $start)
        ->setParameter('end', $end);
        $estimateList = $queryDateSelect->getQuery()->getResult();
        $i = 0;
        foreach ($estimateList as $key => $value) {
            if($value->getBusinessId()->getId() != $businessId[0]->getId()){
                unset($estimateList[$i]);
            };
            $i++;
        }
        
        $estimatenum = count($estimateList);

        
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
        $estimateNumFinal = intval($addZero.$estimatenum+1);
        //dd("D-".$year."-" .$addZero. ($estimatenum+1));
        $estimateNumShow = "D-".$year.$addZero.$estimatenum+1;

        $dressEstimate = new DressEstimate();
        $estimateTab = new EstimateTab();
        // $estimateTab->setEstimateId()
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);
        // $performances = $performanceRepository->findBy(
        //     ['user_id' => $userId]
        // );
        // $clients = $clientRepository->findBy(
        //     ['user_id' => $userId]
        // );
        $repository = $entityManager->getRepository(Business::class);
        $business = $repository->findOneBy(['user_id' => $userId]);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientId = $request->get('clientSelect');
            $client = $clientRepository->findOneBy(
                ['id' => $clientId]
            );
            $i = 1;
            $loopOverPrestations = true;
            while ($loopOverPrestations) {
                if ($request->get('perfNum' . $i) !== null) {
                    $presArray[] = $request->get('perfNum' . $i);
                    $i++;
                } else {
                    break;
                }
            }
            $total = 0;
            $entityManager->persist($estimateTab);
            foreach ($presArray as $key => $value) {
                
                $estimateTabLink = new EstimatePerformanceLink();
                $estimateTabLink->setEstimateTabId($estimateTab->getId());
                $prest = $performanceRepository->findOneBy(
                     ['id' => $value]
                );
                $total += ($prest->getPirce()*$prest->getQuantity()*(1+$prest->getTax()/100));
                $prestId = $prest->getId();
                $estimateTabLink->setPerformanceId($prestId);
                $entityManager->persist($estimateTabLink);
                // $estimateTab->addPerformaceId($prest);
            }
            $total = $total *(1-$dressEstimate->getDiscount()/100);
            $dressEstimate->setTotal($total);
            $dressEstimate->setEstimateNumber($estimateNumFinal);
            $dressEstimate->setUserId($this->getUser());
            $clientId = $request->get('clientSelect');
            $dressEstimate->setClientId($client);
            $entityManager->persist($dressEstimate);
            $estimateTab->setEstimateId($dressEstimate);
            $estimateTab->setBusinessId($business);
            $entityManager->persist($dressEstimate);
            $entityManager->persist($estimateTab);
            //dd($estimateTab, $dressEstimate, $presArray);
            $entityManager->flush();



            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('dress_estimate/new.html.twig', [
            'estimate_number' => $estimateNumShow,
            'dress_estimate' => $dressEstimate,
            'form' => $form,
            // 'performances' => $performances, 
            // 'clients' => $clients
        ]);
    }
    #[Route('/insert/clients', name: 'app_insert_clients', methods: ['GET'])]
    public function insertClients(ClientRepository $clientRepository)
    {
        
        $userId = $this->getUser()->getId();
        // $performances = $performanceRepository->findBy(
        //     ['user_id' => $userId]
        // );
        $clients = $clientRepository->findBy(
            ['user_id' => $userId]
        );
        return $this->render('dress_estimate/select.client.html.twig', [
            // 'performances' => $performances, 
            'clients' => $clients
        ]);
    }
    #[Route('/insert/performances', name: 'app_insert_performances', methods: ['GET'])]
    public function insertPerformances(PerformanceRepository $performanceRepository)
    {
        $userId = $this->getUser()->getId();
        $performances = $performanceRepository->findBy(
            ['user_id' => $userId]
        );
        // $clients = $clientRepository->findBy(
        //     ['user_id' => $userId]
        // ); *
        
        return $this->render('dress_estimate/select.performance.html.twig', [
            // 'performances' => $performances, 
            'performances' => $performances
        ]);
    }
    #[Route('/{id}', name: 'app_dress_estimate_show', methods: ['GET'])]
    public function show(ClientRepository $clientRepository, EstimatePerformanceLinkRepository $estimatePerformanceLink, EstimateTabRepository $estimateTab,EntityManagerInterface $entityManager,  PerformanceRepository $performanceRepository, DressEstimate $dressEstimate, BusinessRepository $businessRepository): Response
    {
        $user = $this->getUser();
        $year = date_format($dressEstimate->getCreationDate(),"Y");
        $estimatenum = $dressEstimate->getEstimateNumber();

        
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

        $businessId = $dressEstimate->getEstimateTab()->getBusinessId()->getId();
        $clientId = $dressEstimate->getClientId()->getId();
        $client = $clientRepository->findOneBy(
            ['id' => $clientId]
        );
        $business = $businessRepository->findOneBy(
            ['id' => $businessId]
        );
        $estimateId = $dressEstimate->getId();
        $estimateTabId = $estimateTab->findBy(
            ['estimate_id' => $estimateId]
        );
        //  dd($estimateTabId[0]->getId());
        $estimateTabId2 = $estimateTabId[0]->getId();
        $performances = $estimatePerformanceLink->findBy(
            ['estimate_tab_id' => $estimateTabId2]
        );
        $logoExt = pathinfo($business->getLogo(),PATHINFO_EXTENSION);
        $userId = $user->getId();
        $logoName = $userId.".".$logoExt;
        $logoExt = pathinfo($user->getSignature(), PATHINFO_EXTENSION);
        $signName = $userId.".".$logoExt;
        $performance  = null;
        for ($i = 0; $i<count($performances); $i++){
            $performance[]= $performanceRepository->findBy(
                ['id'=> $performances[$i]->getPerformanceId()]
            );
        }
        //  $conn = $entityManager->getConnection();
        // $sql = '
        //     SELECT * FROM estimate_tab_performance etp
        //     WHERE etp.estimate_tab_id = :estimateId
        // ';
        // $resultSet = $conn->executeQuery($sql, ['estimateId' => $estimateTabId2->toBinary()]);
        // $resultFecth = $resultSet->fetchAllAssociative();
        // dd($resultFecth[0]['performance_id']);
        //  dd($resultSet->fetchAllAssociative());
        // $estimateTabId = $estimateTab->findOneBy(
        //     ['estimate_id' => $estimateId]
        // );
        // $qb = $entityManager->createQueryBuilder();
        // $qb->select('etp')
        //     ->from('estimate_tab_performance', 'etp')
        //     ->where('etp.estimate_tab_id = :id')
        //     ->setParameter('id', $estimateId , UuidType::NAME);
        // $query = $qb->getQuery();
        //dd($query->execute());

        $estimateNumberShow = "D-".$year."-".$addZero.$estimatenum;
        return $this->render('dress_estimate/show.html.twig', [
            'estimate_num' => $estimateNumberShow,
            'logo_name' => $logoName,
            'sign_name' => $signName,
            'dress_estimate' => $dressEstimate,
            'performances' => $performance,
            'client' => $client,
            'business' => $business,
            'user' => $user,
        ]);
    }
    #[Route('/{id}/pdf', name: 'devis_pdf')]
    public function generateDevisPdf(ClientRepository $clientRepository, EstimatePerformanceLinkRepository $estimatePerformanceLink, EstimateTabRepository $estimateTab,EntityManagerInterface $entityManager,  PerformanceRepository $performanceRepository, DressEstimate $dressEstimate, BusinessRepository $businessRepository, PdfService $pdf){
        $user = $this->getUser();
        $businessId = $dressEstimate->getEstimateTab()->getBusinessId()->getId();
        $clientId = $dressEstimate->getClientId()->getId();
        $client = $clientRepository->findOneBy(
            ['id' => $clientId]
        );
        $business = $businessRepository->findOneBy(
            ['id' => $businessId]
        );
        $estimateId = $dressEstimate->getId();
        $estimateTabId = $estimateTab->findBy(
            ['estimate_id' => $estimateId]
        );
        //  dd($estimateTabId[0]->getId());
          $estimateTabId2 = $estimateTabId[0]->getId();
        $performances = $estimatePerformanceLink->findBy(
            ['estimate_tab_id' => $estimateTabId2]
        );
        for ($i = 0; $i<count($performances); $i++){
            $performance[]= $performanceRepository->findBy(
                ['id'=> $performances[$i]->getPerformanceId()]
            );
        }
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
        // dd($business->getLogo());
        // dd($base64);
        $year = date_format($dressEstimate->getCreationDate(),"Y");
        $estimatenum = $dressEstimate->getEstimateNumber();

        
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
        $estimateNumberShow = "D-".$year."-".$addZero.$estimatenum;
        $html = $this->render('dress_estimate/pdf.html.twig', [
            'estimate_number' => $estimateNumberShow,
            'image' => $base64,
            'extension' => $extension,
            'logo' => $logo64,
            'logoExt' => $logoExtension,
            'dress_estimate' => $dressEstimate,
            'performances' => $performance,
            'client' => $client,
            'business' => $business,
            'user' => $user,
        ]);
        // return $this->render('dress_estimate/pdf.html.twig', [
        //     'dress_estimate' => $dressEstimate,
        //     'performances' => $performance,
        //     'client' => $client,
        //     'business' => $business,
        //     'user' => $user,
        // ]);
        $pdf->showPdfFile($html);
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
          ]);
    }
    #[Route('/{id}/edit', name: 'app_dress_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(BusinessRepository $businessRepository, ClientRepository $clientRepository, EstimatePerformanceLinkRepository $estimatePerformanceLinkRepository, Request $request, DressEstimate $dressEstimate,PerformanceRepository $performanceRepository, EstimateTabRepository $estimateTabRepository, EstimatePerformanceLinkRepository $estimatePerformanceLink, EntityManagerInterface $entityManager): Response
    {

        $userId = $this->getUser()->getId();
        $year = date_format($dressEstimate->getCreationDate(),"Y");
        $estimatenum = $dressEstimate->getEstimateNumber();

        
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
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);
        
        $dressEstimateId = $dressEstimate->getId();
        $estimateTabId = $dressEstimate->getEstimateTab()->getId();
        
        $performance = null;
        $performances = $estimatePerformanceLink->findBy(
            ['estimate_tab_id' => $estimateTabId]
        );

        for ($i = 0; $i<count($performances); $i++){
            $performance[]= $performanceRepository->findBy(
                ['id'=> $performances[$i]->getPerformanceId()]
            );
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $total = 0;
            $presArray[] = 'null';
            $i=1;
            $loopOverPrestations = true;
            //clear all links performance-estimate
            $actualPerfList = $estimatePerformanceLinkRepository->findBy(
                ['estimate_tab_id'=> $estimateTabId] 
            );
            foreach ($actualPerfList as $key => $value) {
                $entityManager->remove($value);
            }
            $entityManager->flush();
            while ($loopOverPrestations) {
                if ($request->get('perfNum' . $i) !== null) {
                    $presArray[] = $request->get('perfNum' . $i);
                    $i++;
                } else {
                    break;
                }
            }
            foreach ($presArray as $key => $value) {
                if ($value != 'null') {
                $estimateTabLink = new EstimatePerformanceLink();
                //$entityManager->persist($estimateTabLink);
                $estimateTabLink->setEstimateTabId($estimateTabId);
               //dd($value);
                $prest = $performanceRepository->findOneBy(
                     ['id' => $value]
                );
                //dd($prest);
                $total += ($prest->getPirce()*$prest->getQuantity()*(1+$prest->getTax()/100));
                $prestId = $prest->getId();
                //dd($prestId);
                $estimateTabLink->setPerformanceId($prestId);
                $entityManager->persist($estimateTabLink);
                // $estimateTab->addPerformaceId($prest);
            }
            }
           
            $entityManager->flush();
            
            
            $clientId = $request->get('clientSelect');

            if($clientId != 'Selectionner le client'){
                
                $client = $clientRepository->findOneBy(
                    ['id' => $clientId]
                );
            }else{
                $client = $clientRepository->findOneBy(
                    ['id' => $dressEstimate->getClientId()->getId()]
                );
            }
            $dressEstimate->setClientId($client);
            $total = $total * (1-$dressEstimate->getDiscount()/100);
            $dressEstimate->setTotal($total);
            $entityManager->persist($dressEstimate);
            $entityManager->flush();
            //dd($total);
            

            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }
        $estimateNumberShow = "D-".$year."-".$addZero.$estimatenum;
        return $this->render('dress_estimate/edit.html.twig', [
            'estimate_number' =>$estimateNumberShow,
            'performances' => $performance,
            'dress_estimate' => $dressEstimate,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_dress_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, DressEstimate $dressEstimate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dressEstimate->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($dressEstimate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
    }
}
