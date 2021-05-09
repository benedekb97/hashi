<?php

declare(strict_types=1);

namespace App\Modifier;

use App\Entity\ConnectionInterface;
use App\Entity\IslandInterface;

interface IslandConnectionModifierInterface
{
    public function connect(IslandInterface $first, IslandInterface $second): ?ConnectionInterface;
}