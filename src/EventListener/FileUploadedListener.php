<?php

namespace App\EventListener;

use App\Entity\Photos;
use App\Event\FileUploadedEvent;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploadedListener implements EventSubscriberInterface
{

    private $url;
    
    public function __construct(
        private SluggerInterface $slugger,
        private string $targetDirectory
    )
    {
     
    }
    public static function getSubscribedEvents(): array
    {
        return [
            FileUploadedEvent::UPLOAD => 'onFileUpload',
            FileUploadedEvent::URL => 'onFileGetUrl',
        ];
    }

    public function onFileUpload(FileUploadedEvent $event)
    {
        $file = $event->getFile();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $this->setUrl($fileName);

        // return $fileName;
    }

    private function setUrl($url) {
        $this->url = $url;
    }

    public function onFileGetUrl() 
    {
        
        return $this->url;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
    public function setTargetDirectory()
    {
        // $this->targetDirectory =  '%kernel.project_dir%/assets/images/';
    }
}