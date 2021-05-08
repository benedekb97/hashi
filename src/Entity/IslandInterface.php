<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableInterface;
use App\Entity\Traits\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface IslandInterface extends ResourceInterface, ArchivableInterface
{
    public function getPoint(): ?PointInterface;

    public function setPoint(PointInterface $point): void;

    public function getTargetBridgeCount(): ?int;

    public function setTargetBridgeCount(?int $targetBridgeCount): void;

    public function getBridgeCount(): ?int;

    public function setBridgeCount(?int $bridgeCount): void;

    public function getGame(): ?GameInterface;

    public function setGame(?GameInterface $game): void;

    public function getConnectedIslands(): Collection;

    public function isConnectedTo(IslandInterface $island): bool;

    public function getConnection(IslandInterface $island): ?ConnectionInterface;

    public function removeConnection(ConnectionInterface $connection): void;
}