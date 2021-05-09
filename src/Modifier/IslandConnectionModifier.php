<?php

declare(strict_types=1);

namespace App\Modifier;

use App\Entity\ConnectionInterface;
use App\Entity\IslandInterface;
use App\Factory\ConnectionFactory;
use Doctrine\ORM\EntityManagerInterface;

class IslandConnectionModifier implements IslandConnectionModifierInterface
{
    private EntityManagerInterface $entityManager;

    private ConnectionFactory $connectionFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        ConnectionFactory $connectionFactory
    ) {
        $this->entityManager = $entityManager;
        $this->connectionFactory = $connectionFactory;
    }

    public function connect(IslandInterface $first, IslandInterface $second): ?ConnectionInterface
    {
        if ($first->isConnectedTo($second)) {
            $connection = $first->getConnection($second);

            $connection->incrementCount();
            $first->setBridgeCount($first->getBridgeCount() + 1);
            $second->setBridgeCount($second->getBridgeCount() + 1);

            $this->entityManager->persist($connection);

            if ($connection->getCount() === 0) {
                $first->removeConnection($connection);
                $second->removeConnection($connection);

                $first->setBridgeCount($first->getBridgeCount() - 3);
                $second->setBridgeCount($second->getBridgeCount() - 3);

                $this->entityManager->remove($connection);

                return null;
            }

            return $connection;
        } else {
            $connection = $this->connectionFactory->createForIslands($first, $second);
            $first->setBridgeCount($first->getBridgeCount() + 1);
            $second->setBridgeCount($second->getBridgeCount() + 1);

            $this->entityManager->persist($connection);

            return $connection;
        }
    }
}