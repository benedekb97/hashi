<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Connection;
use App\Entity\IslandInterface;

class ConnectionFactory
{
    public function createForIslands(IslandInterface $first, IslandInterface $second): Connection
    {
        $connection = new Connection();

        $connection->setCreatedAtNow();
        $connection->setCount(1);
        $connection->setFirstIsland($first);
        $connection->setSecondIsland($second);

        return $connection;
    }
}