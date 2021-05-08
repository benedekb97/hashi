<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ResourceInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, ResourceInterface
{

}