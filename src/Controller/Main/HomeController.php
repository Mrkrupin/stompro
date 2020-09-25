<?php


namespace App\Controller\Main;

use App\Repository\LotRepositoryInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    private $lotRepository;

    public function __construct(LotRepositoryInterface $lotRepository)
    {
        $this->lotRepository = $lotRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['lot'] = $this->lotRepository->getAllLot();
        return $this->render('main/index.html.twig', $forRender);
    }
}