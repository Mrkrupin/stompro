<?php


namespace App\Controller;


use App\Controller\Admin\AdminBaseController;
use App\Entity\Lot;
use http\Message;
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
     * @return RedirectResponse|Response
     */
    public function createLot(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lot = new Lot();
        $form = $this->createForm(Lot::class, $lot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
}