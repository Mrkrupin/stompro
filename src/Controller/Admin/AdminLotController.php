<?php


namespace App\Controller\Admin;


use App\Entity\Lot;
use App\Form\LotType;
use App\Repository\LotRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminLotController extends AdminBaseController
{
    private $lotRepository;

    public function __construct(LotRepositoryInterface $lotRepository)
    {
        $this->lotRepository = $lotRepository;
    }

    /**
     * @Route("/admin/lot", name="admin_lot")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Лоты Аукциона';
        $forRender['lot'] = $this->lotRepository->getAllLot();
        return $this->render('admin/lot/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/lot/create", name="admin_lot_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createLot(Request $request)
    {
        $lot = new Lot();
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $this->lotRepository->setCreateLot($lot, $file);
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
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request)
    {
        $lot = $this->lotRepository->getOneLot($id);
        $form = $this->createForm(LotType::class, $lot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('save')->isClicked()){
                $file = $form->get('image')->getData();
                $this->lotRepository->setUpdateLot($lot, $file);
                $this->addFlash('success', 'Пост обновлен');
            }
            if ($form->get('delete')->isClicked()){
                $this->lotRepository->setDeleteLot($lot);
                $this->addFlash('success', 'Лот удален');
            }
            return $this->redirectToRoute('admin_lot');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактирование лота аукциона';
        $forRender['form'] = $form->createView();
        return $this->render('admin/lot/form.html.twig', $forRender);
    }
}