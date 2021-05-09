<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ResourceInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, ResourceInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_LOGGED_IN = 'ROLE_LOGGED_IN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getEmail(): ?string;

    public function setEmail(string $email): self;

    public function setRoles(array $roles): self;

    public function setPassword(string $password): self;

    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): void;
}