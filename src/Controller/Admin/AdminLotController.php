<?php


namespace App\Controller\Admin;


use App\Entity\Lot;
use App\Form\LotType;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminLotController extends AdminBaseController
{
    /**
     * @Route("/admin/lot", name="admin_lot")
     */
    public function index()
    {
        $lot = $this->getDoctrine()->getRepository(Lot::class)
            ->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Лоты Аукциона';
        $forRender['lot'] = $lot;
        return $this->render('admin/lot/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/lot/create", name="admin_lot_create")
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @return RedirectResponse|Response
     */
    public function createLot(Request $request, FileManagerServiceInterface $fileManagerService)
    {
        $em = $this->getDoctrine()->getManager();
        $lot = new Lot();
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $fileName = $fileManagerService->imageLotUpload($image);
                $lot->setImage($fileName);
            }
            $lot->setCreateAtValue();
            $lot->setIsActive();
            $em->persist($lot);
            $em->flush();
            $this->addFlash('success', 'Лот добавлен');
            return $this->redirectToRoute('admin_lot');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание лота аукциона';
        $forRender['form'] = $form->createView();
        return $this->render('admin/lot/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/lot/update/{id}", name="admin_lot_update")
     * @param int $id
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileManagerServiceInterface $fileManagerService)
    {
        $em = $this->getDoctrine()->getManager();
        $lot = $this->getDoctrine()->getRepository(Lot::class)
            ->find($id);
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('save')->isClicked()){
                $image = $form->get('image')->getData();
                $imageOld = $lot->getImage();
                if ($image){
                    if ($imageOld){
                        $fileManagerService->removeLotImage($imageOld);
                    }
                    $fileName = $fileManagerService->imageLotUpload($image);
                    $lot->setImage($fileName);
                }
                $this->addFlash('success', 'Пост обновлен');
            }
            if ($form->get('delete')->isClicked()){
                $image = $lot->getImage();
                if ($image){
                    $fileManagerService->removeLotImage($image);
                }
                $em->remove($lot);
                $this->addFlash('success', 'Лот удален');
            }
            $em->flush();
            return $this->redirectToRoute('admin_lot');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактирование лота аукциона';
        $forRender['form'] = $form->createView();
        return $this->render('admin/lot/form.html.twig', $forRender);
    }
}