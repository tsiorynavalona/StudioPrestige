<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Form\ContactType;
use App\Entity\Photograph;
use Psr\Log\LoggerInterface;
use App\Service\EmailService;
use App\Service\FileUploader;
use App\Entity\CategoriesPhotos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class FrontController extends AbstractController
{
    private $photos;
    private $category_photo;
    private $photograph;
    private $entity_manager;
    private $fileUploader;

    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $this->photos = new Photos;
        $this->category_photo = new CategoriesPhotos;
        $this->photograph = new Photograph;
        $this->entity_manager = $em;
        $this->fileUploader = $fileUploader;
        // parent::__construct();
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $photos = $this->entity_manager->getRepository($this->photos::class)->findHome();

        return $this->render('front/index.html.twig', [
            'photos' => $photos,
        ]);
    }


    #[Route('/gallery/{label}/{id}', name: 'gallery')]
    public function gallery($id, $label): Response
    {
        $photos = $this->entity_manager->getRepository($this->photos::class)->findByCategory($id);

        return $this->render('front/gallery.html.twig', [
            'title' => $label,
            'photos' => $photos,
        ]);
    }

    #[Route('/gallery/photos/{photo_label}/{id}', name: 'gallery-single')]
    
    public function gallerySingle($id, $photo_label): Response
    {
        $photo  = $this->entity_manager->getRepository($this->photos::class)->findOne($id);
        return $this->render('front/gallery-single.html.twig', [
            'photo' => $photo
        ]);
    }

    #[Route('/services', name: 'services')]

    public function services(): Response
    {
        $categories = $this->entity_manager->getRepository($this->category_photo::class)->findAllWithoutPhotographCategory();
        return $this->render('front/services.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EmailService $emailService, LoggerInterface $logger): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {
            $subject = $contactForm->get('sujet')->getData();
            // $from = 'your_email@example.com';
            $from = $contactForm->get('email')->getData();
            $textBody = $contactForm->get('message')->getData();
            // $htmlBody = '<p>This is the HTML message.</p>';
            try {
                $emailService->sendEmail($subject, $from ,$textBody);
            } catch (TransportExceptionInterface  $e) {
                $logger->error('Email sending failed: ' . $e->getMessage());
            }
            // $emailService->sendEmail($subject, $from, $to, $textBody, $htmlBody);

        // return $this->redirectToRoute('your_route');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $contactForm
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/about.html.twig', []);
    }

   
}
