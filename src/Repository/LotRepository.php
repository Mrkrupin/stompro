<?php

namespace App\Repository;

use App\Entity\Lot;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method Lot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lot[]    findAll()
 * @method Lot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotRepository extends ServiceEntityRepository implements LotRepositoryInterface
{
    private $em;

    private $fm;

    public function __construct(ManagerRegistry $registry,
                                EntityManagerInterface $manager,
                                FileManagerServiceInterface $fileManagerService)
    {
        $this->em = $manager;
        $this->fm = $fileManagerService;
        parent::__construct($registry, Lot::class);
    }


    public function getAllLot(): array
    {
        return parent::findAll();
    }

    public function getOneLot(int $lotId): object
    {
        return parent::find($lotId);
    }

    public function setCreateLot(Lot $lot, UploadedFile $file): object
    {
        if ($file) {
            $filName = $this->fm->imageLotUpload($file);
            $lot->setImage($filName);
        }
        $lot->setCreateAtValue();
        $lot->setIsActive();
        $this->em->persist($lot);
        $this->em->flush();

        return $lot;
    }

    public function setUpdateLot(Lot $lot, UploadedFile $file): object
    {
        $fileName = $lot->getImage();
        if ($file) {
            if ($fileName) {
                $this->fm->removeLotImage($fileName);
            }
            $fileName = $this->fm->imageLotUpload($file);
            $lot->setImage($fileName);
        }
        $this->em->flush();

        return $lot;
    }

    public function setDeleteLot(Lot $lot)
    {
        $fileName = $lot->getImage();
        if ($fileName) {
            $this->fm->removeLotImage($fileName);
        }
        $this->em->remove($lot);
        $this->em->flush();
    }
}
