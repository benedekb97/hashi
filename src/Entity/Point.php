<?php

namespace App\Entity;

use App\Entity\Traits\ResourceTrait;
use App\Repository\PointRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PointRepository::class)
 * @ORM\Table(name="point", uniqueConstraints={@ORM\UniqueConstraint(name="point_unique_position", columns={"vertical_position", "horizontal_position"})})
 */
class Point implements PointInterface
{
    use ResourceTrait;

    /**
     * @ORM\Column(name="vertical_position", type="integer", nullable=true)
     */
    private ?int $verticalPosition = null;

    /**
     * @ORM\Column(name="horizontal_position", type="integer", nullable=true)
     */
    private ?int $horizontalPosition = null;

    public function getVerticalPosition(): ?int
    {
        return $this->verticalPosition;
    }

    public function setVerticalPosition(int $verticalPosition): void
    {
        $this->verticalPosition = $verticalPosition;
    }

    public function getHorizontalPosition(): ?int
    {
        return $this->horizontalPosition;
    }

    public function setHorizontalPosition(int $horizontalPosition): void
    {
        $this->horizontalPosition = $horizontalPosition;
    }
}
