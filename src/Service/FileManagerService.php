<?php


namespace App\Service;


use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{

    private $lotImageDirectory;

    public function __construct($lotImageDirectory)
    {
        $this->lotImageDirectory = $lotImageDirectory;
    }

    /**
     * @return mixed
     */
    public function getLotImageDirectory()
    {
        return $this->lotImageDirectory;
    }

    public function imageLotUpload(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getLotImageDirectory(), $fileName);
        } catch (FileException $exception) {
            return $exception;
        }

        return $fileName;
    }

    public function removeLotImage(string $fileName)
    {
        $fileSystem = new Filesystem();
        $fileImage = $this->getLotImageDirectory() . '' . $fileName;
        try {
            $fileSystem->remove($fileImage);
        } catch (IOExceptionInterface $exception) {
            echo $exception->getMessage();
        }
    }
    /*    public function removeLotImage(string $fileName)
        {
            $fileSystem = new Filesystem();
            $fileImage = $this->getLotImageDirectory().''.$fileName;
            try {
                $fileSystem->remove($fileImage);
            } catch (IOExceptionInterface $exception){
                echo $exception->getMessage();
            }
        }*/
}