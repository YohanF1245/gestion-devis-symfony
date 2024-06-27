<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_users_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword( $passwordHasher->hashPassword($user, $user->getPassword()));
            
            $user->setRoles([]);
            $user->setCreationDate(new DateTime());
            $entityManager->persist($user);
            try{
                $signFile = $form->get("signature")->getData();
                if($signFile){
                    $fileName = $user->getId() . "." . $signFile->getClientOriginalExtension();
                    $signFile->move($this->getParameter('kernel.project_dir').'\assets\public\uploaded-images\signs\\',$fileName);
                    $user ->setSignature($this->getParameter('kernel.project_dir').'\assets\public\uploaded-images\signs\\'.$fileName);
                }
            }catch(\Exception $e){
                echo $e->getMessage();
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(UserPasswordHasherInterface $passwordHasher, Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword( $passwordHasher->hashPassword($user, $user->getPassword()));
            try{
                $signFile = $form->get("signature")->getData();
                if($signFile){
                    $fileName = $user->getId() . "." . $signFile->getClientOriginalExtension();
                    $signFile->move($this->getParameter('kernel.project_dir').'\assets\public\uploaded-images\signs\\',$fileName);
                    $user ->setSignature($this->getParameter('kernel.project_dir').'\assets\public\uploaded-images\signs\\'.$fileName);
                    
                }
            }catch(\Exception $e){
                echo $e->getMessage();
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
