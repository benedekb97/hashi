<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Point;

class PointFactory
{
    public function createNew(): Point
    {
        $point = new Point();

        $point->setCreatedAtNow();

        return $point;
    }
}