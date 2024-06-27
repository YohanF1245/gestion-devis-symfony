<?php
namespace App\Controller;

use App\Entity\Business;
use App\Entity\EstimateTab;
use App\Entity\Users;
use App\Repository\BusinessRepository;
use App\Repository\DressEstimateRepository;
use App\Repository\EstimatePerformanceLinkRepository;
use App\Repository\EstimateTabRepository;
use App\Repository\FactureEmitRepository;
use App\Repository\OutcomeRepository;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController {
    #[Route("/profile", name: "profile")]
    function profile(BusinessRepository $businessRepository, OutcomeRepository $outcomeRepository, Request $request,DressEstimateRepository $dressEstimateRepository, EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $userId= $user->getId();
        
        $business = $businessRepository->findBy([
            'user_id' => $userId,
        ]);
        $userId = $user->getId();
        try{
            $logoExt = pathinfo($business[0]->getLogo(),PATHINFO_EXTENSION);
            $logoName = $userId.".".$logoExt;
        }catch(Exception $e){
            $message = $e->getMessage();
        };
        $logoExt = pathinfo($user->getSignature(), PATHINFO_EXTENSION);
        if($business === []){
            $business[0] = null;
            $logoName = "";
        }
        $signName = $userId.".".$logoExt;
        return $this->render("profile.show.html.twig",
             ['user' => $this->getUser(),
             'business' => $business[0],
             'logo_name' => $logoName,
             'sign_name' => $signName,
             ]
            );
    }
    #[Route("/dashboard", name: "home")]
    function dashboard(FactureEmitRepository $factureEmitRepository,BusinessRepository $businessRepository, OutcomeRepository $outcomeRepository, Request $request,DressEstimateRepository $dressEstimateRepository, EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $userId = $user->getId();

        //setup js chart variable
        $monthArray = array (
            array('Janvier',0,0),
            array('Février',0,0),
            array('Mars',0,0),
            array('Avril',0,0),
            array('Mai',0,0),
            array('Juin',0,0),
            array('Juillet',0,0),
            array('Août',0,0),
            array('Septembre',0,0),
            array('Octobre',0,0),
            array('Novembre',0,0),
            array('Décembre',0,0)
        );
        //income variables
        $estimateList = $dressEstimateRepository->findBy([
            'user_id' => $userId,
        ]);
        
        $totalIncome = 0;
       

        foreach ($estimateList as $estimate) {
            $date = $estimate->getCreationDate();
            $intMonth = intval(date_format($date,'m'));
            $estimateTot = $estimate->getTotal();
            $totalIncome += $estimateTot;
            $monthArray[$intMonth-1][1] = $monthArray[$intMonth-1][1] + $estimateTot;
        }
        //fill invoices variables
        //fill outcome variables
        $outcomeList = [];
        try{
$business = $businessRepository->findBy([
            'user_id' => $userId,
        ]);
        $outcomeList = $outcomeRepository->findBy([
            'business_id' => $business[0]->getId(),
        ]);
        }catch(Exception $e){
            $message =  $e->getMessage();
        };
        

         $totalOutcome = 0;
        foreach ($outcomeList as $outcome){
            $date = $outcome->getOutcomeDate();
            $intMonth = intval(date_format($date, 'm'));
            $outcomeTot = $outcome->getOutcomeAmount();
            $totalOutcome += $outcomeTot;
            $monthArray[$intMonth-1][2] = $monthArray[$intMonth-1][2] + $outcomeTot;
        }
        $estimateList = $dressEstimateRepository->findBy([
            
        'user_id' => $userId],
        ['creation_date' => 'ASC'
    ]);	


        $repository = $entityManager->getRepository(Business::class);
        $business = $repository->findOneBy(
            ['user_id' => $userId]);
        
        return $this->render("dashboard.html.twig",

             ['business' => $business,
            'labels' => $monthArray,
            'totalIncome' => $totalIncome,
            'totalOutcome' => $totalOutcome,
             'estimate_list' => $estimateList,
             'outcome_list' => $outcomeList]
            );
    }

    #[Route("/debugcalc", name:"debugcalc")]
    function debugcalc(EntityManagerInterface $entityManager,EstimateTabRepository $estimateTabRepository,DressEstimateRepository $dressEstimateRepository, EstimatePerformanceLinkRepository $estimatePerformanceLinkRepository, PerformanceRepository $performanceRepository){
        $user = $this->getUser();
        $userId = $user->getId();
        $estimates = $dressEstimateRepository->findBy(['user_id' => $userId]);
        foreach ($estimates as $est) {
            $estimateTab = $estimateTabRepository->findBy(['estimate_id' => $est]);
            $accompte = $est->getAccompte();
            $reduc = $est->getDiscount();
            $estimateId = $est->getId();
            $estimate =  $estimatePerformanceLinkRepository->findBy(['estimate_tab_id' => $estimateTab[0]->getId()]);
            $total = 0;
            foreach($estimate as $performance){
                $perfId = $performance->getPerformanceId();
                $perf = $performanceRepository->findBy(['id'=> $perfId]);
                $perfTotal = ($perf[0]->getQuantity() * $perf[0]->getPirce())*(1+$perf[0]->getTax()/100);
                $total+= $perfTotal;
            }
            $total = $total * (1-$reduc/100);
            $est->setTotal($total);
            $entityManager->persist($est);
            $entityManager->flush();

        }
        return new HttpFoundationResponse('Montant totaux des devis recalculés ! ');
    }
}

?>