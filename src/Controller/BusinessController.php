<?php

namespace App\Controller;

use App\Entity\Business;
use App\Form\BusinessType;
use App\Repository\BusinessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/business')]
class BusinessController extends AbstractController
{
    #[Route('/', name: 'app_business_index', methods: ['GET'])]
    public function index(BusinessRepository $businessRepository): Response
    {
        return $this->render('business/index.html.twig', [
            'businesses' => $businessRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_business_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $business = new Business();
        $form = $this->createForm(BusinessType::class, $business);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            try{
                $logo = $form->get("logo")->getData();
                if($logo){
                    $fileName = $user->getId() . "." . $logo->getClientOriginalExtension();
                    $logo->move($this->getParameter('kernel.project_dir').'assets\public\uploaded-images\logos\\',$fileName);
                    $business ->setLogo($this->getParameter('kernel.project_dir').'assets\public\uploaded-images\logos\\'.$fileName);
                }
            }catch(\Exception $e){
                echo $e->getMessage();
            }
        $business->setUserId($user);
            $entityManager->persist($business);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('business/new.html.twig', [
            'business' => $business,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_show', methods: ['GET'])]
    public function show(Business $business): Response
    {
        return $this->render('business/show.html.twig', [
            'business' => $business,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_business_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Business $business, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BusinessType::class, $business);
        $form->handleRequest($request);
        try{
            $user = $this->getUser();
            $logo = $form->get("logo")->getData();
            if($logo){
                $fileName = $user->getId() . "." . $logo->getClientOriginalExtension();
                $logo->move($this->getParameter('kernel.project_dir').'assets\public\uploaded-images\logos\\',$fileName);
                $business ->setLogo($this->getParameter('kernel.project_dir').'assets\public\uploaded-images\logos\\'.$fileName);
            }
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_business_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('business/edit.html.twig', [
            'business' => $business,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_delete', methods: ['POST'])]
    public function delete(Request $request, Business $business, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$business->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($business);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_business_index', [], Response::HTTP_SEE_OTHER);
    }
}
