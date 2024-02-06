<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Service\EmailService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
// use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Service\JWTService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityUserController extends AbstractController
{
    private $entityManager;
    private $usersRepository;
    private $tokenStorage;


    public function __construct(UserRepository $usersRepository, EntityManagerInterface $entityManager, private Security $security, TokenStorageInterface $tokenStorage) {
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
      
    }
   
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        // $success_registration = $this->flashBag->get('success_registration');
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(FlashBagInterface $flashBag): Response
    {
        // $flashBag = $flashBag->getFlashBag(); // Get the Flash Bag

     

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotten-password', name:'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        
        TokenGeneratorInterface $tokenGenerator,
        
        EmailService $mail
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $this->usersRepository->findOneByEmailAndVerification($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if($user){
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // On crée les données du mail
                $context = compact('url', 'user');

            

                // Envoi du mail
                $mail->sendEmail(
                    'Réinitialisation de mot de passe sur StudioPrestige',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    $context,
                    'password-reset',
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            // $user est null
            $this->addFlash('danger', 'Utilisateur non trouvé');
            // return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }


    #[Route('/forgotten-password/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $this->usersRepository->findOneByResetToken($token);
        
        // On vérifie si l'utilisateur existe

        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                
                
        // On enregistre le nouveau mot de passe en le hashant
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        
        // Si le token est invalide on redirige vers le login
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }

    #[Route('profile/edit-password', name:'edit_password')]
    public function edit_password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = $this->entityManager->getRepository(User::class)->findOneById($this->getUser()->getId());
        // dd($user);

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success-password-change', 'Mot de passe bien changé. Veuillez vous reconnecter');
            $this->tokenStorage->setToken(null);
            return $this->redirectToRoute('app_login');
            
            // return $this->redirectToRoute('app_login');

        }
        
        // On vérifie si l'utilisateur existe

        
        // Si le token est invalide on redirige vers le login
        
        return $this->render('security/edit_password.html.twig', ['passForm' => $form]);
    }

    #[Route('profile/edit-infos', name:'edit_infos')]
    public function edit_infos(Request $request, UserPasswordHasherInterface $passwordHasher, JWTService $jwt, EmailService $mail): Response
    {
        
        $user = $this->entityManager->getRepository(User::class)->findOneById($this->getUser()->getId());
        $user_id = $user->getId();
        // dd($user);
        $currentUserEmail = strtolower($user->getEmail());

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
 
            $userEmail = strtolower($form->get('email')->getData());
            // dd($currentUserEmail, $userEmail);

            if($currentUserEmail != $userEmail) {
                // dd('ok');
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];
    
                // On crée le Payload
                $payload = [
                    'user_id' => $user_id
                ];
    
                // On génère le token
                $base_url = $this->getParameter('app.base_url');
                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                $this->addFlash('user_confirm_mail', $user);
    
                $mail->sendEmail(
                    'Lien de confirmation sur StudioPrestige',
                    $user->getEmail(),
                    'Activation de votre nouveau email sur notre site',
                    compact('user', 'token', 'base_url'),
                    'verify-mail'
                );
    
    
    
                return $this->redirectToRoute('verify', [
                    'message' => 'email_send'
                ]);
            }
    
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        
            // $this->addFlash('success-password-change', 'Mot de passe bien changé. Veuillez vous reconnecter');
            // $this->tokenStorage->setToken(null);
            // return $this->redirectToRoute('app_login');
            
            // return $this->redirectToRoute('app_login');

        }
        
        // On vérifie si l'utilisateur existe

        
        // Si le token est invalide on redirige vers le login
        
        return $this->render('security/edit_info.html.twig', ['form' => $form]);
    }
}
