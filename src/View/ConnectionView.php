<?php

declare(strict_types=1);

namespace App\View;

class ConnectionView extends View
{
    public ?int $connectedIslandId = null;

    public ?ConnectionIslandView $firstIsland = null;

    public ?ConnectionIslandView $secondIsland = null;

    public ?int $count = null;
    
    public ?AxisView $axis = null;
}