<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\JWTService;
use App\Service\EmailService;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    private $user;
    private $em;


    public function __construct(EntityManagerInterface $em) {
        $this->user = new User;
        $this->em = $em;

    }

    #[Route('/registration', name: 'registration')]
    public function index(Request $request, UserPasswordHasherInterface  $passwordEncoder, JWTService $jwt, EmailService $mail): Response
    {

        $form = $this->createForm(RegistrationType::class, $this->user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->user;
            $password = $form->get('password')->getData();
            $hashedPassword  = $passwordEncoder->hashPassword($user, $password);

            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $base_url = $this->getParameter('app.base_url');
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            $mail->sendEmail(
                'Lien de confirmation sur StudioPrestige',
                $user->getEmail(),
                'Activation de votre compte sur le site e-commerce',
                compact('user', 'token', 'base_url'),
                'register'
            );



            return $this->redirectToRoute('verify', [
                'message' => 'email_send'
            ]);
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form,
        ]);
       
    }

    #[Route('/verify/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, Session $session): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $this->em->getRepository($this->user::class)->findOneById($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->isIsVerified()) {
                $user->setIsVerified(true);
                $this->em->flush($user);

                $message = 'Votre compte est activé. Veuillez-vous connecter ici';

                $flashes = $session->getFlashBag();

                // add flash messages
                $flashes->add(
                    'success_registration',
                    $message
                );
              
               
                // $this->addFlash('success_registration', $message);
                return $this->redirectToRoute('app_login');
            }


            // $message = null; 
            
        } 
        // Ici un problème se pose dans le token
        
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('verify', [
            'message' => 'error_token'
        ]);
       
    }

    #[Route('/verify-mail/{token}', name: 'verify_mail_user')]
    public function verifyMailUser($token, JWTService $jwt, Session $session): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $this->em->getRepository($this->user::class)->findOneById($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user) {
                $flashes = $session->getFlashBag();
                if ($flashes->has('user_confirm_mail')) { // Check if the 'success' type exists
                    // Access the message(s) using $flashBag->get('success')
                    $user_infos = $flashes->get('user_confirm_mail')[0];
                    // dd($user_infos->getId());
                    $user->setUsername($user_infos->getUsername());
                    $user->setEmail($user_infos->getEmail());
                    $user->setPhone($user_infos->getPhone());
                    $user->setPhone2($user_infos->getPhone2());
                    $user->setAddress($user_infos->getAddress());
                    
                    $this->em->persist($user);
                    $this->em->flush();
                    $this->addFlash('mail_changed_success', 'Mail bien modifié, reconnectez-vous avec');
                    return $this->redirectToRoute('user_profile');
                }
               
                // $this->addFlash('success_registration', $message);
                // return $this->redirectToRoute('app_login');
            }


            // $message = null; 
            
        } 
        // Ici un problème se pose dans le token
        
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('verify', [
            'message' => 'error_token'
        ]);
       
    }


    #[Route('/registration/verify/{message}', name: 'verify')]
    public function verify(Request $request, $message = ''): Response
    {
        
            return $this->render('registration/verify.html.twig', [
                // 'form' => $form,
                'message' => $message

            ]);
       
    }
}
