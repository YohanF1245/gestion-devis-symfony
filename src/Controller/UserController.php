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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route("/user", name: "create_user")]
    public function createUser(EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $user->setMail("test@test.com");
        //$user->setPassword("devine");
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
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher){
        $form = $this->createForm(CreateUserType::class);
        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $data->setCreationDate(new DateTime("now"));
            $hachedPassword = $passwordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hachedPassword);
            $data->setRoles([]);
            $em->persist($data);
            try{

                $signFile = $form->get("signature")->getData();
                $fileName = $data->getId() . "." . $signFile->getClientOriginalExtension();
                $signFile->move($this->getParameter('kernel.project_dir').'\uploaded-images\signs\\',$fileName);
                $data ->setSignature($this->getParameter('kernel.project_dir').'uploaded-images/signs'.$fileName);
            }catch(\Exception $e){
                echo $e->getMessage();
            }
            dd($data);
            $em->persist($data);
            $em->flush();
        }

        return $this->render("user/signup.html.twig", [
            "form"=>$form,
        ]);
     }
}
