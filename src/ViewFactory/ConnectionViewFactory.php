<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\ConnectionInterface;
use App\Entity\EntityInterface;
use App\Entity\IslandInterface;
use App\View\ConnectionView;

class ConnectionViewFactory extends AbstractViewFactory
{
    private ?IslandInterface $island = null;

    private AxisViewFactory $axisViewFactory;

    public function __construct(
        AxisViewFactory $axisViewFactory
    ){
        $this->axisViewFactory = $axisViewFactory;
    }

    public function setPrimaryIsland(?IslandInterface $island): void
    {
        $this->island = $island;
    }

    /**
     * {@inheritDoc}
     *
     * @param ConnectionInterface $connection
     * @return ConnectionView
     */
    public function create(EntityInterface $connection): ConnectionView
    {
        $view = new ConnectionView();

        if (isset($this->island)) {
            $view->connectedIslandId = $connection->getFirstIsland() === $this->island
                ? $connection->getSecondIsland()->getId()
                : $connection->getFirstIsland()->getId();
        } else {
            $view->firstIslandId = $connection->getFirstIsland()->getId();
            $view->secondIslandId = $connection->getSecondIsland()->getId();
        }

        $view->count = $connection->getCount();
        $view->axis = $this->axisViewFactory->create($connection->getAxis());

        return $view;
    }
}