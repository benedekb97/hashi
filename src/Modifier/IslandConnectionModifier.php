<?php

declare(strict_types=1);

namespace App\Modifier;

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

    public function connect(IslandInterface $first, IslandInterface $second): void
    {
        if ($first->isConnectedTo($second)) {
            $connection = $first->getConnection($second);

            $connection->incrementCount();

            $this->entityManager->persist($connection);

            if ($connection->getCount() === 0) {
                $first->removeConnection($connection);
                $second->removeConnection($connection);

                $this->entityManager->remove($connection);
            }
        } else {
            $connection = $this->connectionFactory->createForIslands($first, $second);

            $this->entityManager->persist($connection);
        }
    }
}