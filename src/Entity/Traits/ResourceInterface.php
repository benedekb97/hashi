<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\EntityInterface;
use DateTimeInterface;

interface ResourceInterface extends EntityInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAtNow(): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAtNow(): void;
}