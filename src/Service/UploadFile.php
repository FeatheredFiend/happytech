<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectoryCV;
    private $targetDirectoryPDF;
    private $slugger;

    public function __construct($targetDirectoryCV, $targetDirectoryPDF, SluggerInterface $slugger)
    {
        $this->targetDirectoryCV = $targetDirectoryCV;
        $this->targetDirectoryPDF = $targetDirectoryPDF;
        $this->slugger = $slugger;
    }

    public function uploadCV(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryCV(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadPDF(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryPDF(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }


    public function getTargetDirectoryCV()
    {
        return $this->targetDirectoryCV;
    }

    public function getTargetDirectoryPDF()
    {
        return $this->targetDirectoryPDF;
    }
}