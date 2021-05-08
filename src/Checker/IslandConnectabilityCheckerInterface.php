<?php

declare(strict_types=1);

namespace App\Checker;

use App\Entity\IslandInterface;

interface IslandConnectabilityCheckerInterface
{
    public function check(IslandInterface $first, IslandInterface $second): bool;
}