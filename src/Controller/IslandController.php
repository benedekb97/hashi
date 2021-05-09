<?php

declare(strict_types=1);

namespace App\Controller;

use App\Checker\IslandConnectabilityChecker;
use App\Checker\IslandConnectabilityCheckerInterface;
use App\Entity\IslandInterface;
use App\Modifier\IslandConnectionModifierInterface;
use App\Repository\IslandRepository;
use App\ViewFactory\ConnectionViewFactory;
use App\ViewFactory\IslandViewFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\b;

class IslandController extends Controller
{
    private IslandRepository $islandRepository;

    private IslandViewFactory $islandViewFactory;

    private IslandConnectabilityCheckerInterface $islandConnectabilityChecker;

    private IslandConnectionModifierInterface $islandConnectionModifier;

    public function __construct(
        EntityManagerInterface $entityManager,
        IslandRepository $islandRepository,
        IslandViewFactory $islandViewFactory,
        IslandConnectabilityChecker $islandConnectabilityChecker,
        IslandConnectionModifierInterface $islandConnectionModifier
    ){
        $this->islandRepository = $islandRepository;
        $this->islandViewFactory = $islandViewFactory;
        $this->islandConnectabilityChecker = $islandConnectabilityChecker;
        $this->islandConnectionModifier = $islandConnectionModifier;

        parent::__construct($entityManager);
    }

    public function indexAction(Request $request): Response
    {
        $game = $request->get('gameId');

        $islands = $this->islandRepository->findAllByGame($game);

        $viewContainer = [];

        /** @var IslandInterface $island */
        foreach ($islands as $island) {
            $viewContainer[] = $this->islandViewFactory->create($island);
        }

        return new JsonResponse($viewContainer, Response::HTTP_OK);
    }

    public function showAction(Request $request): Response
    {
        $game = $request->get('gameId');
        $island = $request->get('id');

        $island = $this->islandRepository->findByGameIdAndId($game, $island);

        if ($island === null) {
            return new JsonResponse([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'The island could not be found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $view = $this->islandViewFactory->create($island);

        return new JsonResponse($this->serialize($view), Response::HTTP_OK);
    }

    public function connectAction(Request $request): Response
    {
        $island = $request->get('id');
        $direction = $request->request->get('direction');

        if (!array_key_exists($direction, IslandInterface::DIRECTION_MAP)) {
            return new JsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => sprintf('Unknown direction: %s', $direction)
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var IslandInterface $island */
        $island = $this->islandRepository->find($island);
        $connectTo = $this->islandRepository->findNearestToIslandByDirection($island, $direction);

        if (null === $connectTo) {
            return new JsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message'=> sprintf('No visible islands in \'%s\' direction', $direction),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->islandConnectabilityChecker->check($island, $connectTo)) {
            return new JsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => sprintf('No visible islands in \'%s\' direction', $direction),
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->islandConnectionModifier->connect($island, $connectTo);

        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}