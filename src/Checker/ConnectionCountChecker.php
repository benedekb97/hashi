<?php

declare(strict_types=1);

namespace App\Checker;

use App\Entity\IslandInterface;

class ConnectionCountChecker implements IslandConnectabilityCheckerInterface
{
    public function check(IslandInterface $first, IslandInterface $second): bool
    {
        if ($first->isConnectedTo($second)) {
            return $first->getConnection($second)->getCount() < 2;
        }
        
        return true;
    }
}