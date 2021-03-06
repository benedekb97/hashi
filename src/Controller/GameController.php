<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserInterface;
use App\Repository\GameRepository;
use App\ViewFactory\GameViewFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    private GameRepository $gameRepository;

    private GameViewFactory $gameViewFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        GameRepository $gameRepository,
        GameViewFactory $gameViewFactory
    ) {
        $this->gameRepository = $gameRepository;
        $this->gameViewFactory = $gameViewFactory;

        parent::__construct($entityManager);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request): Response
    {
        $game = (int)$request->get('id');

        $game = $this->gameRepository->findByUserAndId($this->user(), $game);

        if ($game === null) {
            return new JsonResponse([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'The game could not be found!',
            ], Response::HTTP_NOT_FOUND);
        }

        $view = $this->gameViewFactory->create($game);

        return new JsonResponse($this->serialize($view));
    }

    public function indexAction(Request $request): Response
    {
        /** @var UserInterface $user */
        if (($user = $this->user()) === null) {
            return new JsonResponse([
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You are not logged in!',
            ], Response::HTTP_FORBIDDEN);
        }

        $views = [];

        foreach ($user->getGames() as $game) {
            $views[] = $this->gameViewFactory->create($game);
        }

        return new JsonResponse(
            $this->serialize($views),
            Response::HTTP_OK
        );
    }
}