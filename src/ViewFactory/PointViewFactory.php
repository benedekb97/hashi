<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\Entity\PointInterface;
use App\Entity\Traits\ResourceInterface;
use App\View\PointView;
use App\View\View;

class PointViewFactory extends AbstractViewFactory
{
    /**
     * {@inheritDoc}
     *
     * @param PointInterface $point
     * @return View
     */
    public function create(EntityInterface $point): PointView
    {
        $view = new PointView();

        $view->horizontalPosition = $point->getHorizontalPosition();
        $view->verticalPosition = $point->getVerticalPosition();
        $view->id = $point->getId();

        return $view;
    }
}