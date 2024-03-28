<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\CreateUserType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    #[Route("/user", name: "create_user")]
    public function createUser(EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $user->setMail("test@test.com");
        $user->setPass("devine");
        $user->setPseudo("toto");
        $user->setCreationDate(new DateTime("now"));

        $entityManager->persist($user);

        $entityManager->flush();

        return new Response("Utilisateur créé avec l'id : ".$user->getId());
    }

    #[Route("/user/1", name: "show_user")]
    public function show(EntityManagerInterface $entityManager, string $pseudo )
    {
        $user = $entityManager->getRepository(Users::class)->find($pseudo);
        if(!$user){
            throw $this->createNotFoundException("no user foudn");
        }
        return new Response("user create at : ".$user->getCreationDate());
    }
    #[Route("/user/submit/signup", name:"user.create")]
    public function signup(Request $request, EntityManagerInterface $em){
        $form = $this->createForm(CreateUserType::class);
        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()){
            $data = new Users();
            $signFile = $form->get("signature")->getData();
            dd($signFile->getClientOriginalName(), $signFile->getClientOriginalExtension());
            $data = $form->getData();
            $data->setCreationDate(new DateTime("now"));
              $em->persist($data);
             $em->flush();
        }

        return $this->render("user/signup.html.twig", [
            "form"=>$form,
        ]);
     }
}
