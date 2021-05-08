<?php

namespace App\Controller;

use App\Checker\IslandConnectabilityChecker;
use App\Checker\IslandConnectabilityCheckerInterface;
use App\Factory\PointFactory;
use App\Modifier\IslandConnectionModifierInterface;
use App\Repository\IslandRepository;
use App\Repository\PointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    private PointRepository $pointRepository;

    private PointFactory $pointFactory;

    private IslandRepository $islandRepository;

    private IslandConnectabilityCheckerInterface $islandConnectabilityChecker;

    private IslandConnectionModifierInterface $islandConnectionModifier;

    public function __construct(
        EntityManagerInterface $entityManager,
        PointRepository $pointRepository,
        PointFactory $pointFactory,
        IslandRepository $islandRepository,
        IslandConnectabilityChecker $islandConnectabilityChecker,
        IslandConnectionModifierInterface $islandConnectionModifier
    ){
        $this->pointRepository = $pointRepository;
        $this->pointFactory = $pointFactory;
        $this->islandRepository = $islandRepository;
        $this->islandConnectabilityChecker = $islandConnectabilityChecker;
        $this->islandConnectionModifier = $islandConnectionModifier;

        parent::__construct($entityManager);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
