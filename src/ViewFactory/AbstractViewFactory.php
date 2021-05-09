<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\View\View;

abstract class AbstractViewFactory
{
    abstract public function create(EntityInterface $entity): View;
}