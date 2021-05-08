<?php

declare(strict_types=1);

namespace App\Entity;

class Axis implements AxisInterface
{
    private string $axis;

    private int $position;

    public function __construct(string $axis, int $position)
    {
        $this->axis = $axis;
        $this->position = $position;
    }
    
    public function getAxis(): string
    {
        return $this->axis;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}