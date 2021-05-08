<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTimeInterface;

interface ResourceInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAtNow(): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAtNow(): void;
}