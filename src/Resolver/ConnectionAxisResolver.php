<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Axis;
use App\Entity\AxisInterface;
use App\Entity\IslandInterface;

class ConnectionAxisResolver
{
    public function resolve(IslandInterface $first, IslandInterface $second): AxisInterface
    {
        return $first->getPoint()->getHorizontalPosition() === $second->getPoint()->getHorizontalPosition()
            ? new Axis(AxisInterface::AXIS_VERTICAL, $first->getPoint()->getHorizontalPosition())
            : new Axis(AxisInterface::AXIS_HORIZONTAL, $first->getPoint()->getVerticalPosition());
    }
}