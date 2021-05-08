<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ResourceInterface;

interface PointInterface extends ResourceInterface
{
    public function getHorizontalPosition(): ?int;

    public function setHorizontalPosition(int $horizontalPosition): void;

    public function getVerticalPosition(): ?int;

    public function setVerticalPosition(int $verticalPosition): void;
}