<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableTrait;
use App\Entity\Traits\ResourceTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\IslandRepository;

/**
 * @ORM\Entity(repositoryClass=IslandRepository::class)
 * @ORM\Table(name="island")
 */
class Island implements IslandInterface
{
    use ResourceTrait;
    use ArchivableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Point", cascade={"all"}, fetch="LAZY")
     */
    private ?PointInterface $point = null;

    /**
     * @ORM\Column(name="target_bridge_count", type="integer")
     */
    private ?int $targetBridgeCount = null;

    /**
     * @ORM\Column(name="bridge_count", type="integer")
     */
    private ?int $bridgeCount = null;

    /**
     * @ORM\ManyToOne(targetEntity="Game", cascade={"all"}, fetch="LAZY")
     */
    private ?GameInterface $game = null;

    public function getPoint(): ?PointInterface
    {
        return $this->point;
    }

    public function setPoint(PointInterface $point): void
    {
        $this->point = $point;
    }

    public function getTargetBridgeCount(): ?int
    {
        return $this->targetBridgeCount;
    }

    public function setTargetBridgeCount(?int $targetBridgeCount): void
    {
        $this->targetBridgeCount = $targetBridgeCount;
    }

    public function getBridgeCount(): ?int
    {
        return $this->bridgeCount;
    }

    public function setBridgeCount(?int $bridgeCount): void
    {
        $this->bridgeCount = $bridgeCount;
    }

    public function getGame(): ?GameInterface
    {
        return $this->game;
    }

    public function setGame(?GameInterface $game): void
    {
        $this->game = $game;
    }
}