<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationAdminCheckListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
      
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Get the authenticated user
        $user = $event->getAuthenticationToken()->getUser();

        // Access the session and add a variable
        $session = $event->getRequest()->getSession();
        
        if(in_array('ROLE_ADMIN', $user->getRoles())) {
            $session->set('user_admin_id', $user->getUsername());
        }
        
        // You can add more session variables as needed

        // Additional logic after successful authentication
        // ...

        // Symfony will handle the session persistence, so no need to manually save the session
    }

 
}