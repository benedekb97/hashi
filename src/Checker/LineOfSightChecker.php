<?php

declare(strict_types=1);

namespace App\Checker;

use App\Entity\IslandInterface;

class LineOfSightChecker implements IslandConnectabilityCheckerInterface
{
    public function check(IslandInterface $first, IslandInterface $second): bool
    {
        return $first->getPoint()->getVerticalPosition() === $second->getPoint()->getVerticalPosition() ||
            $first->getPoint()->getHorizontalPosition() === $second->getPoint()->getHorizontalPosition();
    }
}