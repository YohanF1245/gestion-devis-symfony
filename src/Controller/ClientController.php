<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, EntityManagerInterface $em ): Response
    {
        $userId = $this->getUser()->getId();
        //$clientRepository->countWhere($userId);
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findBy(
                ['user_id' => $userId]
            ),
            
        ]);
    }
    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser();
            $client->setUserId($userId);
            $entityManager->persist($client);
            $entityManager->flush();

        //return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    
    }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/new/modal', name: 'app_client_new_modal', methods: ['GET', 'POST'])]
    public function new_modal(RequestStack $requestStack, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $request = $requestStack->getMainRequest();
        $client = new Client();
        //$form = $this->createForm(ClientType::class, $client);
        $form = $this->createForm(ClientType::class, $client, [
            'action' => $this->generateUrl('app_dress_estimate_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser();
            $client->setUserId($userId);
            $entityManager->persist($client);
            $entityManager->flush();
            $referer = $request->headers->get('referer');         
            //return new RedirectResponse($referer);
            //$response = $this->redirect($this->generateUrl('app_client_index'));
            
        $userId = $this->getUser()->getId();
        $clients = $clientRepository->findBy(
            ['user_id' => $userId]
        );
            
            $response = $this->redirectToRoute('app_dress_estimate_new', ['clients' => $clients]);
           //return $response;
        //return $this->redirectToRoute('app_client_index', []);
        
//return  new Response();
    }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{success}', name:'app_client_success', methods:  ['GET'])]
    public function success(): Response
    {
        return new Response(
            '<div onload="operationSuccess()"></div>'
        );
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        
        dd("");
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    
    
    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
