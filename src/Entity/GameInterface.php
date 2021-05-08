<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableInterface;
use App\Entity\Traits\ResourceInterface;
use App\Entity\Traits\UserAwareInterface;
use Doctrine\Common\Collections\Collection;

interface GameInterface extends ResourceInterface, UserAwareInterface, ArchivableInterface
{
    public function getIslands(): Collection;

    public function hasIsland(IslandInterface $island): bool;

    public function addIsland(IslandInterface $island): void;

    public function removeIsland(IslandInterface $island): void;
}