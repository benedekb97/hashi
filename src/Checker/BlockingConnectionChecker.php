<?php

declare(strict_types=1);

namespace App\Checker;

use App\Entity\AxisInterface;
use App\Entity\ConnectionInterface;
use App\Entity\IslandInterface;
use App\Repository\ConnectionRepository;
use App\Resolver\ConnectionAxisResolver;

class BlockingConnectionChecker implements IslandConnectabilityCheckerInterface
{
    private ConnectionRepository $connectionRepository;

    private ConnectionAxisResolver $connectionAxisResolver;

    public function __construct(
        ConnectionRepository $connectionRepository,
        ConnectionAxisResolver $connectionAxisResolver
    ) {
        $this->connectionRepository = $connectionRepository;
        $this->connectionAxisResolver = $connectionAxisResolver;
    }

    public function check(IslandInterface $first, IslandInterface $second): bool
    {
        $targetAxis = $this->connectionAxisResolver->resolve($first, $second);

        [$min, $max] = $this->getPositions($first, $second);

        $connections = $this->connectionRepository->findAllForGame($first->getGame());

        /** @var ConnectionInterface $connection */
        foreach ($connections as $connection) {
            if (
                $connection->getAxis()->getAxis() !== $targetAxis->getAxis() &&
                $connection->getAxis()->getPosition() > $min &&
                $connection->getAxis()->getPosition() < $max
            ) {
                dd('blocking');
                return false;
            }
        }

        return true;
    }

    private function getPositions(IslandInterface $first, IslandInterface $second): array
    {
        $axis = $this->connectionAxisResolver->resolve($first, $second);

        if ($axis->getAxis() === AxisInterface::AXIS_HORIZONTAL) {
            return $first->getPoint()->getHorizontalPosition() > $second->getPoint()->getHorizontalPosition()
                ? [$second->getPoint()->getHorizontalPosition(), $first->getPoint()->getHorizontalPosition()]
                : [$first->getPoint()->getHorizontalPosition(), $second->getPoint()->getHorizontalPosition()];
        } else {
            return $first->getPoint()->getVerticalPosition() > $second->getPoint()->getVerticalPosition()
                ? [$second->getPoint()->getVerticalPosition(), $first->getPoint()->getVerticalPosition()]
                : [$first->getPoint()->getVerticalPosition(), $second->getPoint()->getVerticalPosition()];
        }
    }
}