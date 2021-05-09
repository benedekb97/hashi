<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\Entity\IslandInterface;
use App\View\ConnectionIslandView;

class ConnectionIslandViewFactory extends AbstractViewFactory
{
    private PointViewFactory $pointViewFactory;

    public function __construct(
        PointViewFactory $pointViewFactory
    ) {
        $this->pointViewFactory = $pointViewFactory;
    }

    /**
     * {@inheritDoc}
     *
     * @param IslandInterface $island
     * @return ConnectionIslandView
     */
    public function create(EntityInterface $island): ConnectionIslandView
    {
        $view = new ConnectionIslandView();

        $view->point = $this->pointViewFactory->create($island->getPoint());

        return $view;
    }
}