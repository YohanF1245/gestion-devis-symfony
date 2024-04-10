<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController {

    #[Route("/dashboard", name: "home")]
    function dashboard(Request $request){
        return $this->render("dashboard.html.twig");
    }

}

?>