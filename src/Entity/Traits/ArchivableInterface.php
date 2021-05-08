<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTimeInterface;

interface ArchivableInterface
{
    public function getArchivedAt(): ?DateTimeInterface;

    public function isArchived(): bool;
    
    public function archive(): void;

    public function restore(): void;
}