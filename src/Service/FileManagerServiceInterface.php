<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerServiceInterface
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function imageLotUpload(UploadedFile $file): string;

    /**
     * @param string $fileName
     * @return mixed
     */
    public function removeLotImage(string $fileName);
}