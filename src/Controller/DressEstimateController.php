<?php

namespace App\Controller;

use App\Entity\DressEstimate;
use App\Form\DressEstimateType;
use App\Repository\ClientRepository;
use App\Repository\DressEstimateRepository;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Config\TwigConfig;

#[Route('/dress/estimate')]
class DressEstimateController extends AbstractController
{
    #[Route('/', name: 'app_dress_estimate_index', methods: ['GET'])]
    public function index(DressEstimateRepository $dressEstimateRepository): Response
    {
        return $this->render('dress_estimate/index.html.twig', [
            'dress_estimates' => $dressEstimateRepository->findAll(),
        ]);
    }
 
    #[Route('/new', name: 'app_dress_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, PerformanceRepository $performanceRepository, EntityManagerInterface $entityManager): Response
    {
        $dressEstimate = new DressEstimate();
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);
        $userId = $this->getUser()->getId();
        $performances = $performanceRepository->findBy(
            ['user_id' => $userId]
        );
        $clients = $clientRepository->findBy(
            ['user_id' => $userId]
        );  
        if ($form->isSubmitted() && $form->isValid()) {
            $clientId = $request->get('clientSelect');
            $i = 1;
            $loopOverPrestations = true;
            $prestArray = [];
            while($loopOverPrestations){
                if($request->get('perfNum'.$i) !== null){
                    $presArray[] = $request->get('perfNum'.$i);
                    $i++;
                }else{
                    break;
                }
            }
            dd($presArray);
            $entityManager->persist($dressEstimate);
            $entityManager->flush();
            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('dress_estimate/new.html.twig', [
            'dress_estimate' => $dressEstimate,
            'form' => $form,
            // 'performances' => $performances, 
            // 'clients' => $clients
        ]);
    }
    #[Route('/insert/clients', name: 'app_insert_clients', methods: ['GET'])]
    public function insertClients(ClientRepository $clientRepository){
        $userId = $this->getUser()->getId();
        // $performances = $performanceRepository->findBy(
        //     ['user_id' => $userId]
        // );
        $clients = $clientRepository->findBy(
            ['user_id' => $userId]
        ); 
        return $this->render('dress_estimate/select.client.html.twig', [
            // 'performances' => $performances, 
            'clients' => $clients
        ]);
    }
    #[Route('/insert/performances', name: 'app_insert_performances', methods: ['GET'])]
    public function insertPerformances(PerformanceRepository $performanceRepository){
        $userId = $this->getUser()->getId();
        $performances = $performanceRepository->findBy(
            ['user_id' => $userId]
        );
        // $clients = $clientRepository->findBy(
        //     ['user_id' => $userId]
        // ); 
        return $this->render('dress_estimate/select.performance.html.twig', [
            // 'performances' => $performances, 
            'performances' => $performances
        ]);
    }
    #[Route('/{id}', name: 'app_dress_estimate_show', methods: ['GET'])]
    public function show(DressEstimate $dressEstimate): Response
    {
        return $this->render('dress_estimate/show.html.twig', [
            'dress_estimate' => $dressEstimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dress_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DressEstimate $dressEstimate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DressEstimateType::class, $dressEstimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('dress_estimate/edit.html.twig', [
            'dress_estimate' => $dressEstimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dress_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, DressEstimate $dressEstimate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dressEstimate->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($dressEstimate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dress_estimate_index', [], Response::HTTP_SEE_OTHER);
    }
}
