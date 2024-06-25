<?php
namespace App\Controller;

use App\Entity\Business;
use App\Entity\Users;
use App\Repository\DressEstimateRepository;
use App\Repository\EstimatePerformanceLinkRepository;
use App\Repository\EstimateTabRepository;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController {

    #[Route("/dashboard", name: "home")]
    function dashboard(Request $request,DressEstimateRepository $dressEstimateRepository, EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $userId = $user->getId();

        $estimateList = $dressEstimateRepository->findBy([
        'user_id' => $userId],
        ['creation_date' => 'ASC'
    ]);	


        $repository = $entityManager->getRepository(Business::class);
        $business = $repository->findOneBy(
            ['user_id' => $userId]);
        
        return $this->render("dashboard.html.twig",

             ['business' => $business,
             'estimate_list' => $estimateList]
            
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