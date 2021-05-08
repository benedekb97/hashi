<?php

namespace App\Entity;

use App\Entity\Traits\ArchivableTrait;
use App\Entity\Traits\ResourceTrait;
use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConnectionRepository::class)
 */
class Connection implements ConnectionInterface
{
    use ResourceTrait;
    use ArchivableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Island")
     * @ORM\JoinColumn(name="first_island_id", nullable=true)
     */
    private ?IslandInterface $firstIsland = null;

    /**
     * @ORM\ManyToOne(targetEntity="Island")
     * @ORM\JoinColumn(name="second_island_id", nullable=true)
     */
    private ?IslandInterface $secondIsland = null;

    /**
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private ?int $count = null;

    public function getFirstIsland(): ?IslandInterface
    {
        return $this->firstIsland;
    }

    public function setFirstIsland(IslandInterface $island): void
    {
        $this->firstIsland = $island;
    }

    public function getSecondIsland(): ?IslandInterface
    {
        return $this->secondIsland;
    }

    public function setSecondIsland(IslandInterface $island): void
    {
        $this->secondIsland = $island;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getAxis(): AxisInterface
    {
        return $this->firstIsland->getPoint()->getHorizontalPosition() === $this->secondIsland->getPoint()->getHorizontalPosition()
            ? new Axis(AxisInterface::AXIS_VERTICAL, $this->firstIsland->getPoint()->getHorizontalPosition())
            : new Axis(AxisInterface::AXIS_HORIZONTAL, $this->firstIsland->getPoint()->getVerticalPosition());
    }

    public function incrementCount(): void
    {
        if ($this->count === 2) {
            $this->count = 0;
        } else {
            $this->count++;
        }
    }
}
