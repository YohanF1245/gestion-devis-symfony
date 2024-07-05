<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class CvController extends AbstractController {
    
    #[Route("/cv", name: "cv")]
    function cv(){
        
        return $this->render("cv.html.twig");
    }

}

?>