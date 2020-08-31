<?php


namespace App\Repository;


use App\Entity\Lot;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface LotRepositoryInterface
{
    /**
     * @return Lot[]
     */
    public function getAllLot(): array;

    /**
     * @param int $lotId
     * @return Lot
     */
    public function getOneLot(int $lotId): object;

    /**
     * @param Lot $lot
     * @param UploadedFile $file
     * @return Lot
     */
    public function setCreateLot(Lot $lot, UploadedFile $file): object;

    /**
     * @param Lot $lot
     * @param UploadedFile $file
     * @return Lot
     */
    public function setUpdateLot(Lot $lot, UploadedFile $file): object;

    /**
     * @param Lot $lot
     * @param string $fileName
     */
    public function setDeleteLot(Lot $lot);
}