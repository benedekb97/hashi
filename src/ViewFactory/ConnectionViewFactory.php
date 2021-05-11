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

    private ConnectionIslandViewFactory $connectionIslandViewFactory;

    public function __construct(
        AxisViewFactory $axisViewFactory,
        ConnectionIslandViewFactory $connectionIslandViewFactory
    ){
        $this->axisViewFactory = $axisViewFactory;
        $this->connectionIslandViewFactory = $connectionIslandViewFactory;
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
            $view->firstIsland = $this->connectionIslandViewFactory->create($connection->getFirstIsland());
            $view->secondIsland = $this->connectionIslandViewFactory->create($connection->getSecondIsland());
        }

        $view->count = $connection->getCount();
        $view->axis = $this->axisViewFactory->create($connection->getAxis());
        $view->id = $connection->getId();

        return $view;
    }
}