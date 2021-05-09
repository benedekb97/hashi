<?php

declare(strict_types=1);

namespace App\Entity;

interface AxisInterface extends EntityInterface
{
    public const AXIS_HORIZONTAL = 'horizontal';
    public const AXIS_VERTICAL = 'vertical';

    public function getAxis(): string;

    public function getPosition(): int;
}