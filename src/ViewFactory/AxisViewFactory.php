<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\AxisInterface;
use App\Entity\EntityInterface;
use App\View\AxisView;

class AxisViewFactory extends AbstractViewFactory
{
    /**
     * {@inheritDoc}
     *
     * @param AxisInterface $entity
     * @return AxisView
     */
    public function create(EntityInterface $entity): AxisView
    {
        $view = new AxisView();

        $view->position = $entity->getPosition();
        $view->axis = $entity->getAxis();

        return $view;
    }
}