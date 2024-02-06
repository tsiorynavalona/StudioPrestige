<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAdminController extends AbstractController
{
    #[Route(path: 'admin/login', name: 'app_admin_login')]
    public function admin_login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/admin-login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: 'admin/logout', name: 'admin_app_logout')]
    public function logout(Request $request): void
    {
        $session = $request->getSession();
        $session->remove('user_admin_id'); 
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
