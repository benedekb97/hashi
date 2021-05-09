<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableInterface;
use App\Entity\Traits\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface IslandInterface extends ResourceInterface, ArchivableInterface
{
    public const DIRECTION_UP = 'up';
    public const DIRECTION_DOWN = 'down';
    public const DIRECTION_LEFT = 'left';
    public const DIRECTION_RIGHT = 'right';

    public const DIRECTION_MAP = [
        self::DIRECTION_UP => 'Up',
        self::DIRECTION_DOWN => 'Down',
        self::DIRECTION_LEFT => 'Left',
        self::DIRECTION_RIGHT => 'Right',
    ];

    public function getPoint(): ?PointInterface;

    public function setPoint(PointInterface $point): void;

    public function getTargetBridgeCount(): ?int;

    public function setTargetBridgeCount(?int $targetBridgeCount): void;

    public function getBridgeCount(): ?int;

    public function setBridgeCount(?int $bridgeCount): void;

    public function getGame(): ?GameInterface;

    public function setGame(?GameInterface $game): void;
    
    public function hasGame(): bool;

    public function getConnectedIslands(): Collection;

    public function isConnectedTo(IslandInterface $island): bool;

    public function getConnection(IslandInterface $island): ?ConnectionInterface;

    public function removeConnection(ConnectionInterface $connection): void;
}