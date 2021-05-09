<?php

declare(strict_types=1);

namespace App\View;

use DateTimeInterface;

class GameView extends View
{
    public ?int $id = null;

    public ?UserView $user = null;

    public ?DateTimeInterface $createdAt = null;

    public ?DateTimeInterface $updatedAt = null;

    public ?DateTimeInterface $archivedAt = null;
}