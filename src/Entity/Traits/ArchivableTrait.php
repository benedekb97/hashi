<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTimeInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait ArchivableTrait
{
    /**
     * @ORM\Column(name="archived_at", type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $archivedAt = null;

    public function getArchivedAt(): ?DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function isArchived(): bool
    {
        return isset($this->archivedAt);
    }

    public function archive(): void
    {
        $this->archivedAt = new DateTime();
    }
    
    public function restore(): void
    {
        $this->archivedAt = null;
    }
}