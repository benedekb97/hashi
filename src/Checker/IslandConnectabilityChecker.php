<?php

declare(strict_types=1);

namespace App\Checker;

use App\Entity\IslandInterface;

class IslandConnectabilityChecker implements IslandConnectabilityCheckerInterface
{
    private array $checkers = [];

    public function __construct(
        LineOfSightChecker $lineOfSightChecker,
        ConnectionCountChecker $connectionCountChecker,
        BlockingConnectionChecker $blockingConnectionChecker
    ) {
        $this->checkers[] = $lineOfSightChecker;
        //$this->checkers[] = $connectionCountChecker;
        $this->checkers[] = $blockingConnectionChecker;
    }

    public function check(IslandInterface $first, IslandInterface $second): bool
    {
        /** @var IslandConnectabilityCheckerInterface $checker */
        foreach ($this->checkers as $checker) {
            if (!$checker->check($first, $second)) {
                return false;
            }
        }

        return true;
    }
}