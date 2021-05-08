<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableInterface;
use App\Entity\Traits\ResourceInterface;

interface ConnectionInterface extends ResourceInterface, ArchivableInterface
{
    public const AXIS_HORIZONTAL = 'horizontal';
    public const AXIS_VERTICAL = 'vertical';

    public function getFirstIsland(): ?IslandInterface;

    public function setFirstIsland(IslandInterface $island): void;

    public function getSecondIsland(): ?IslandInterface;

    public function setSecondIsland(IslandInterface $island): void;

    public function getCount(): ?int;

    public function setCount(int $count): void;

    public function getAxis(): AxisInterface;

    public function incrementCount(): void;
}