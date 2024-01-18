<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ErrorController extends AbstractController
{
    private $tokenStorage;
    private $errorRenderer;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        // $this->errorRenderer = $container->get('error_renderer');
        // parent::__construct();
    }


    public function showError(\Throwable $exception, Request $request, DebugLoggerInterface $logger = null): Response
    {


        $pathInfo = $request->getPathInfo();

        $session = $request->getSession();
        if ($exception instanceof NotFoundHttpException) {
            if (strpos($pathInfo, '/admin/') === 0 && $session->has('user_admin_id')) {
                return $this->render('errors/error404admin.html.twig', []);
            }
            return $this->render('errors/error404.html.twig', []);
        }

       
    }

   
}