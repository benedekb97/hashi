<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ArchivableTrait;
use App\Entity\Traits\ResourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="firstIsland")
     */
    private Collection $primaryConnections;

    /**
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="secondIsland")
     */
    private Collection $secondaryConnections;

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

    public function hasGame(): bool
    {
        return isset($this->game);
    }

    public function getConnectedIslands(): Collection
    {
        $islands = new ArrayCollection();

        /** @var ConnectionInterface $connection */
        foreach ($this->primaryConnections as $connection) {
            if ($connection->getFirstIsland() === $this && !$islands->contains($connection->getSecondIsland())) {
                $islands->add($connection->getSecondIsland());
            } elseif ($connection->getSecondIsland() === $this && !$islands->contains($connection->getFirstIsland())) {
                $islands->add($connection->getFirstIsland());
            }
        }

        /** @var ConnectionInterface $connection */
        foreach ($this->secondaryConnections as $connection) {
            if ($connection->getFirstIsland() === $this && !$islands->contains($connection->getSecondIsland())) {
                $islands->add($connection->getSecondIsland());
            } elseif ($connection->getSecondIsland() === $this && !$islands->contains($connection->getFirstIsland())) {
                $islands->add($connection->getFirstIsland());
            }
        }

        return $islands;
    }

    public function isConnectedTo(IslandInterface $island): bool
    {
        return $this->getConnectedIslands()->contains($island);
    }

    public function getConnection(IslandInterface $island): ?ConnectionInterface
    {
        /** @var ConnectionInterface $connection */
        foreach ($this->primaryConnections as $connection) {
            if ($connection->getFirstIsland() === $this && $connection->getSecondIsland() === $island) {
                return $connection;
            }
        }

        /** @var ConnectionInterface $connection */
        foreach ($this->secondaryConnections as $connection) {
            if ($connection->getSecondIsland() === $this && $connection->getFirstIsland() === $island) {
                return $connection;
            }
        }

        return null;
    }

    public function removeConnection(ConnectionInterface $connection): void
    {
        if ($this->primaryConnections->contains($connection)) {
            $this->primaryConnections->removeElement($connection);
            $connection->setFirstIsland(null);
        }

        if ($this->secondaryConnections->contains($connection)) {
            $this->secondaryConnections->removeElement($connection);
            $connection->setSecondIsland(null);
        }
    }

    public function getConnections(): Collection
    {
        $connections = new ArrayCollection();

        /** @var ConnectionInterface $connection */
        foreach ($this->primaryConnections as $connection) {
            if (!$this->hasUniqueConnection($connections, $connection)) {
                $connections->add($connection);
            }
        }

        /** @var ConnectionInterface $connection */
        foreach ($this->secondaryConnections as $connection) {
            if (!$this->hasUniqueConnection($connections, $connection)) {
                $connections->add($connection);
            }
        }

        return $connections;
    }

    private function hasUniqueConnection(Collection $collection, ConnectionInterface $connection): bool
    {
        /** @var ConnectionInterface $con */
        foreach ($collection as $con) {
            if (
                ($con->getFirstIsland() === $connection->getFirstIsland() && $con->getSecondIsland() === $connection->getSecondIsland()) ||
                ($con->getFirstIsland() === $connection->getSecondIsland() && $con->getSecondIsland() === $connection->getFirstIsland())
            ) {
                return true;
            }
        }

        return false;
    }
}