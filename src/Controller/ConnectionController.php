<?php

declare(strict_types=1);

namespace App\Controller;

use App\Checker\IslandConnectabilityChecker;
use App\Checker\IslandConnectabilityCheckerInterface;
use App\Modifier\IslandConnectionModifierInterface;
use App\Repository\IslandRepository;
use App\ViewFactory\ConnectionViewFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConnectionController extends Controller
{
    private IslandRepository $islandRepository;

    private IslandConnectabilityCheckerInterface $islandConnectabilityChecker;

    private IslandConnectionModifierInterface $islandConnectionModifier;

    private ConnectionViewFactory $connectionViewFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        IslandRepository $islandRepository,
        IslandConnectabilityChecker $islandConnectabilityChecker,
        IslandConnectionModifierInterface $islandConnectionModifier,
        ConnectionViewFactory $connectionViewFactory
    ) {
        $this->islandRepository = $islandRepository;
        $this->islandConnectabilityChecker = $islandConnectabilityChecker;
        $this->islandConnectionModifier = $islandConnectionModifier;
        $this->connectionViewFactory = $connectionViewFactory;

        parent::__construct($entityManager);
    }

    public function createAction(Request $request): Response
    {
        $firstIsland = $request->request->get('firstIsland');
        $secondIsland = $request->request->get('secondIsland');

        $firstIsland = $this->islandRepository->find($firstIsland);
        $secondIsland = $this->islandRepository->find($secondIsland);

        if ($firstIsland === null || $secondIsland === null) {
            return new JsonResponse([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Island could not be found!'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$this->islandConnectabilityChecker->check($firstIsland, $secondIsland)) {
            return new JsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Islands cannot be connected!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $connection = $this->islandConnectionModifier->connect($firstIsland, $secondIsland);

        $this->entityManager->flush();

        if ($connection === null) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $view = $this->connectionViewFactory->create($connection);

        return new JsonResponse(
            $this->serialize($view),
            $connection->getCount() === 1
                ? Response::HTTP_CREATED
                : Response::HTTP_OK
        );
    }
}