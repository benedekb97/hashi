<?php

namespace App\Entity;

use App\Entity\Traits\ArchivableTrait;
use App\Entity\Traits\ResourceTrait;
use App\Entity\Traits\UserAwareTrait;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game implements GameInterface
{
    use ResourceTrait;
    use UserAwareTrait;
    use ArchivableTrait;

    /**
     * @ORM\OneToMany(targetEntity="Island", mappedBy="game", cascade={"all"}, orphanRemoval=true)
     */
    private Collection $islands;

    public function __construct()
    {
        $this->islands = new ArrayCollection();
    }

    public function getIslands(): Collection
    {
        return $this->islands;
    }

    public function hasIsland(IslandInterface $island): bool
    {
        return $this->islands->contains($island);
    }

    public function addIsland(IslandInterface $island): void
    {
        if (!$this->hasIsland($island)) {
            $island->setGame($this);
            $this->islands->add($island);
        }
    }

    public function removeIsland(IslandInterface $island): void
    {
        if ($this->hasIsland($island)) {
            $island->setGame(null);
            $this->islands->removeElement($island);
        }
    }
}
