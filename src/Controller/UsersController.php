<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Security\EmailVerifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/users')]
class UsersController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }
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
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@example.com', 'AcmeMailBot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation.email.html.twig')
                    
            );
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route(path: '/verify/email', name: 'app_verify_email')]
    public function emailConfirmation(Request $request, TranslatorInterface $translator): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_login');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        //return $this->redirectToRoute('app_register');
        return $this->render('security/verify.email.html.twig');
    }
    #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        // $email = (new TemplatedEmail())
        //     ->from(new Address('mailer@example.com', 'AcmeMailBot'))
        //     ->to('yohan.ferdin@hotmail.fr')
        //     ->subject('Test Email')
        //     ->htmlTemplate('emails/test.html.twig');

        $email = (new Email())
            ->from('unpigeon@avecundevis.com  ')
            ->to('yohan.ferdin@hotmail.fr')
            ->subject('Test Email')
            ->html('<p>Test Email</p>');
        try {
            $mailer->send($email);
            return new Response('Email sent successfully');
        } catch (\Exception $e) {
            return new Response('Failed to send email: '.$e->getMessage());
        }
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
