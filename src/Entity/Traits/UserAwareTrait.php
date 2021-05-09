<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

trait UserAwareTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    protected ?UserInterface $user = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function hasUser(): bool
    {
        return isset($this->user);
    }
}