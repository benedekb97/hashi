<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\Entity\GameInterface;
use App\Entity\Traits\ResourceInterface;
use App\View\GameView;

class GameViewFactory extends AbstractViewFactory
{
    private UserViewFactory $userViewFactory;

    public function __construct(
        UserViewFactory $userViewFactory
    ) {
        $this->userViewFactory = $userViewFactory;
    }

    /**
     * {@inheritDoc}
     *
     * @param GameInterface $game
     * @return GameView
     */
    public function create(EntityInterface $game): GameView
    {
        $view = new GameView();

        if ($game->hasUser()) {
            $view->user = $this->userViewFactory->create($game->getuser());
        }

        $view->id = $game->getId();
        $view->createdAt = $game->getCreatedAt();
        $view->updatedAt = $game->getUpdatedAt();
        $view->archivedAt = $game->getArchivedAt();

        return $view;
    }
}