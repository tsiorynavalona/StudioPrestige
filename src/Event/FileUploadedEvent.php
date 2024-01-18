<?php

namespace App\Event;

use App\Entity\Photos;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadedEvent extends Event
{
    public const UPLOAD = 'file.uploaded';
    public const URL = 'file.url';

    protected $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getFile(): UploadedFile
    {

        return $this->file;
   
    }
}