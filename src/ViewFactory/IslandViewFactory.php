<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\Entity\IslandInterface;
use App\Entity\Traits\ResourceInterface;
use App\View\View;
use App\View\IslandView;

class IslandViewFactory extends AbstractViewFactory
{
    private PointViewFactory $pointViewFactory;

    private ConnectionViewFactory $connectionViewFactory;

    public function __construct(
        PointViewFactory $pointViewFactory,
        ConnectionViewFactory $connectionViewFactory
    ) {
        $this->pointViewFactory = $pointViewFactory;
        $this->connectionViewFactory = $connectionViewFactory;
    }

    /**
     * {@inheritDoc}
     *
     * @param IslandInterface $island
     * @return View
     */
    public function create(EntityInterface $island): IslandView
    {
        $view = new IslandView();

        $view->point = $this->pointViewFactory->create($island->getPoint());

        $view->bridgeCount = $island->getBridgeCount();
        $view->targetBridgeCount = $island->getTargetBridgeCount();

        $view->gameId = $island->hasGame() ? $island->getGame()->getId() : null;

        $view->id = $island->getId();
        $view->connections = $this->getConnections($island);

        return $view;
    }

    public function getConnections(IslandInterface $island): array
    {
        $connections = [];

        $this->connectionViewFactory->setPrimaryIsland($island);

        foreach ($island->getConnections() as $connection) {
            $connections[] = $this->connectionViewFactory->create($connection);
        }

        return $connections;
    }
}