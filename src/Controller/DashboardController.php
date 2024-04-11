<?php
namespace App\Controller;

use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController {

    #[Route("/dashboard", name: "home")]
    function dashboard(Request $request, EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $userId = $user->getId();
        $repository = $entityManager->getRepository(Business::class);
        $business = $repository->findOneBy(['user_id' => $userId]);
        
        return $this->render("dashboard.html.twig", ['business' => $business]);
    }

}

?>