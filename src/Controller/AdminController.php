<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Form\PhotoType;
use App\Entity\Photograph;
use App\Form\PhotographType;
use App\Service\FileUploader;
use App\Form\CategoryPhotoType;
use Doctrine\ORM\EntityManager;
use App\Entity\CategoriesPhotos;
use App\Event\FileUploadedEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\EventListener\FileUploadedListener;
use App\Form\FilterPhotosType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', requirements: ['_locale' => 'en|es|fr'], name: 'admin_')]
class AdminController extends AbstractController
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
    public function index(Request $request): Response
    {

        // dd($this->getUser());

        // dump($request);
       
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Photos',
        ]);
    }
    //photo_controller

    #[Route('/photos', name: 'photos_list')]
    public function list(Request $request, PaginatorInterface $paginator): Response
    {

        $photos_query = $this->entity_manager->getRepository($this->photos::class)->findAll();

        $filterForm = $this->createForm(FilterPhotosType::class);

        $filterForm->handleRequest($request);

        if($filterForm->isSubmitted()) {
            $categories_filter = $filterForm->get('categories')->getData();
            $photograph_filter = $filterForm->get('photograph')->getData();
            // dd($categories_filter);
            if($categories_filter != null || $photograph_filter != null) {
                $photos_query =  $this->entity_manager->getRepository($this->photos::class)->findWithFilter($categories_filter, $photograph_filter);
            }     
            // dd($photos_query);
        }

        $photos = $paginator->paginate(
            $photos_query, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            8 // Nombre de résultats par page
        );

        // dump($request);
       
        return $this->render('admin/list.html.twig', [
            'photos' => $photos,
            'filterForm' => $filterForm
        ]);
    }

    #[Route('/photos/add', name: 'add_photos')]
    public function add(Request $request, EventDispatcherInterface $dispatcher, FileUploadedListener $fileUploadedListener, SluggerInterface $slug): Response
    {

        // dump($request);

        $form = $this->createForm(PhotoType::class, $this->photos);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
       

            $image_file = $form->get('upload')->getData();

            if ($image_file) {
                $event = new FileUploadedEvent($image_file);

                $dispatcher->dispatch($event, FileUploadedEvent::UPLOAD);

                $imageFileName = $fileUploadedListener->onFileGetUrl();

                $photo = $this->photos;

                $photo->setTitle($form->get('title')->getData());
                $alt = $form->get('alt')->getData() != '' ? $form->get('alt')->getData() :  $form->get('title')->getData();
                $photo->setAlt($slug->slug($alt));
                $photo->setUrl('/images/photos/'.$imageFileName);
                // $photo->setIdPhotograph($form->get('id_photograph')->getData());
                // $photo->addCategoriesPhoto($form->get('categoriesPhotos')->getData());

                $this->entity_manager->persist($photo);
                $this->entity_manager->flush();
                
                return $this->redirectToRoute('admin_photos_list');

            }
        }

        return $this->render('admin/add_photos.html.twig', [
            'form_photos' => $form

        ]);
    }
    #[Route('/photos/edit', name: 'edit_photos')]
    public function editPhoto(Request $request, EventDispatcherInterface $dispatcher, FileUploadedListener $fileUploadedListener, SluggerInterface $slug): Response
    {

        // dump($request);
        $id = $request->query->get('id');

        $photo = $this->entity_manager->getRepository($this->photos::class)->findOne($id);

        

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
       

            $image_file = $form->get('upload')->getData();

            if ($image_file) {
                $event = new FileUploadedEvent($image_file);

                $dispatcher->dispatch($event, FileUploadedEvent::UPLOAD);

                $imageFileName = $fileUploadedListener->onFileGetUrl();
                $photo->setUrl('/images/photos/'.$imageFileName);

            }

            $photo->setTitle($form->get('title')->getData());
            $photo->setClient($form->get('client')->getData());
            $photo->setDescriptions($form->get('descriptions')->getData());
            $alt = $form->get('alt')->getData() != '' ? $form->get('alt')->getData() :  $form->get('title')->getData();
            $photo->setAlt($slug->slug($alt));
            
            // $photo->setIdPhotograph($form->get('id_photograph')->getData());
            // $photo->addCategoriesPhoto($form->get('categoriesPhotos')->getData());

            $this->entity_manager->persist($photo);
            $this->entity_manager->flush();
            
            return $this->redirectToRoute('admin_photos_list');
        }

        return $this->render('admin/edit_photos.html.twig', [
            'form_photos' => $form

        ]);
       
        
    }
    #[Route('/photos/delete', name: 'delete_photos')]
    public function deletePhoto(Request $request, EventDispatcherInterface $dispatcher, FileUploadedListener $fileUploadedListener, SluggerInterface $slug): Response
    {

        // dump($request);
        $id = $request->query->get('id');
        $photo = $this->entity_manager->getRepository($this->photos::class)->findOne($id);
        $this->entity_manager->remove($photo);
        $this->entity_manager->flush();
            
        return $this->redirectToRoute('admin_photos_list');
 
    }
    //end_photo_controller

    // category_photos_controller
    #[Route('/category_photos', name: 'category_list')]
    public function categoryList(Request $request): Response
    {
        $categories  = $this->entity_manager->getRepository($this->category_photo::class)->findAllWithoutPhotographCategory('admin');
        return $this->render('admin/list_category.html.twig', [
            'categories' => $categories
        ]);
    }

   
    #[Route('/category/add', name: 'add_category_photo')]
    public function addCategoryPhoto(Request $request): Response
    {

        $form = $this->createForm(CategoryPhotoType::class, $this->category_photo, ['validation_groups' => 'add']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $image = $form->get('image')->getData();
            
            $category = $this->category_photo;
            if($image) {
                $imageFileName = $this->fileUploader->upload($image);
                $category->setImage('/images/photos/'.$imageFileName);
            }
    
            $this->entity_manager->persist($category);
            $this->entity_manager->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/add_category_photos.html.twig', [
                        // 'controller_name' => 'Photos',
                        'form_category' => $form
                    ]);
        
    }

    #[Route('/category/edit/{id}', name: 'edit_category_photo')]
    public function editCategoryPhoto($id, Request $request): Response
    {
        $category = $this->entity_manager->getRepository($this->category_photo::class)->findByOne($id);

        $form = $this->createForm(CategoryPhotoType::class, $category, ['validation_groups' => 'edit']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $image = $form->get('image')->getData();
            
            if($image) {
                $imageFileName = $this->fileUploader->upload($image);
                $category->setImage('/images/photos/'.$imageFileName);
            }
    
            $this->entity_manager->persist($category);
            $this->entity_manager->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/add_category_photos.html.twig', [
                        // 'controller_name' => 'Photos',
                        'form_category' => $form
                    ]);
    }
    
    #[Route('/category/inline-edit/', name: 'edit_inline_category_photo')]
    public function editInlineCategoryPhoto(Request $request): Response
    {

        // if ($request->isXmlHttpRequest()) {
            // Process your AJAX request here
            $data = $request->request->all(); // Access posted data
            $id = $data['id'];
            $label = $data['label'];
            $category = $this->entity_manager->getRepository($this->category_photo::class)->findByOne($id);
            $category->setLabel($label);

            // Perform some action based on the data received
            // For example, do some processing and prepare a response
            $responseData = [
                'message' => 'AJAX request received!',
                'data' => $data,
            ];

            $this->entity_manager->persist($category);

            $this->entity_manager->flush();
            
            // You can return JSON response
            return new JsonResponse($responseData);
        // }
    }
    
    #[Route('/category/delete/', name: 'delete_category_photo')]
    public function deleteCategoryPhoto(Request $request): Response
    {

        $id = $request->query->get('id'); 
        $category = $this->entity_manager->getRepository($this->category_photo::class)->findByOne($id);
        $this->entity_manager->remove($category);
        $this->entity_manager->flush();

        return $this->redirectToRoute('admin_category_list');
    }
    //photograph controller

    #[Route('/photograph', name: 'photograph')]
    public function photographList(Request $request): Response
    {
        $photographs = $this->entity_manager->getRepository($this->photograph::class)->findAll();
        // dd($photographs);
       
        return $this->render('admin/list_photograph.html.twig', [
            'photographs' => $photographs
            
        ]);
    }

    #[Route('/photograph/add', name: 'add_photograph')]
    public function photographAdd(Request $request): Response
    {
        // $photographs = $this->entity_manager->getRepository($this->photograph::class);
        $form = $this->createForm(PhotographType::class, $this->photograph,  ['validation_groups' => 'add']);

        $form->handleRequest($request);
        $category = $this->entity_manager->getRepository($this->category_photo::class)->findPhotographCategory();
        if($category == null) {
            $newCategoryPhotograph = $this->category_photo;
            $newCategoryPhotograph->setLabel('photographe');
            $newCategoryPhotograph->setDescriptions('photographe');
            $newCategoryPhotograph->setImage('photographe');

            $this->entity_manager->persist($newCategoryPhotograph);

            $this->entity_manager->flush();
        }


        if($form->isSubmitted() && $form->isValid()) {

            $image_file = $form->get('image')->getData();
            // dd($category);
            if ($image_file) {
                $name = $form->get('name')->getData();
                $imageFileName = $this->fileUploader->upload($image_file);

                $photograph_img_profile = $this->photos;
                $photograph = $this->photograph;

                $photograph_img_profile->setTitle($name);
                $photograph_img_profile->addCategoriesPhoto($category);
                $photograph_img_profile->setUrl('/images/photos/'.$imageFileName);
                $photograph_img_profile->setClient($imageFileName);
                $photograph_img_profile->setAlt($name);

                $this->entity_manager->persist($photograph_img_profile);
                $photograph->setName($name);

                $photograph->setImageProfile($photograph_img_profile);
                $this->entity_manager->persist($photograph);

                $this->entity_manager->flush();

                return $this->redirectToRoute('admin_photograph');

            }
        }

        return $this->render('admin/add_photograph.html.twig', [
            'photograph_form' => $form
            
        ]);
    }

    #[Route('/photograph/edit', name: 'edit_photograph')]
    public function photographEdit(Request $request): Response
    {
        $id = $request->query->get('id');

        $photograph = $this->entity_manager->getRepository($this->photograph::class)->findOneById($id);

        
       
        $form = $this->createForm(PhotographType::class, $photograph, ['validation_groups' => 'edit']);

        $form->handleRequest($request);

        // if($form->isSubmitted()) {
        //     dd($form->get('image_profile')->getData());

        // }

      
        if($form->isSubmitted() && $form->isValid()) {
            
            $name = $form->get('name')->getData();

            $image_file = $form->get('image')->getData();
            // dd($category);
            if ($image_file) {
                
                $imageFileName = $this->fileUploader->upload($image_file);
                $photograph_img_profile = $this->photos;
                $photograph_img_profile->setTitle($name);
           
                $photograph_img_profile->setUrl('/images/photos/'.$imageFileName);
                $photograph_img_profile->setClient($imageFileName);
                $photograph->setImageProfile($photograph_img_profile);

                $this->entity_manager->persist($photograph_img_profile);
                
            } else {
                $photograph_img_profile = $photograph->getImageProfile();
            }

               

                
                // $photograph = $photograph;

                
                // $photograph_img_profile->setAlt($name);

                $photograph_img_profile->setAlt($name);
                $photograph->setName($name);

                
                $this->entity_manager->persist($photograph);

                $this->entity_manager->flush();

                return $this->redirectToRoute('admin_photograph');

     
            
           
        }
        return $this->render('admin/edit_photograph.html.twig', [
            'photograph_form' => $form
            
        ]);
    }

    #[Route('/photograph/delete', name: 'delete_photograph')]
    public function photographDelete(Request $request): Response
    {
        $id = $request->query->get('id') ? $request->query->get('id') : null;

        if ($id == null) {
            throw $this->createNotFoundException('No id found');
        }

        $photograph = $this->entity_manager->getRepository($this->photograph::class)->findOneById($id);

        $this->entity_manager->remove($photograph);
        $this->entity_manager->flush();

    
        return $this->redirectToRoute('admin_photograph');
    }
}
