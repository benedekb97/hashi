<?php

declare(strict_types=1);

namespace App\View;

class IslandView extends View
{
    public ?PointView $point = null;

    public ?int $targetBridgeCount = null;

    public ?int $bridgeCount = null;
    
    public ?int $gameId = null;
    
    public ?int $id = null;

    public array $connections = [];
}