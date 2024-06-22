<?php
namespace App\Controller;

use App\Entity\Business;
use App\Entity\Users;
use App\Repository\DressEstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

}

?>