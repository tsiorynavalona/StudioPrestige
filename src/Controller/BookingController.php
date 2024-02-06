<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Photograph;
use App\Service\FileUploader;
use App\Entity\CategoriesPhotos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
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

    #[Route('/booking', name: 'booking')]
    public function booking(): Response
    {
        $photographs = $this->entity_manager->getRepository($this->photograph::class)->findAll();
        return $this->render('front/booking.html.twig', [
            // 'photographs' => $photographs
        ]);
    }
}
