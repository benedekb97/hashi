<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Island;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Island|null find($id, $lockMode = null, $lockVersion = null)
 * @method Island|null findOneBy(array $criteria, array $orderBy = null)
 * @method Island[]    findAll()
 * @method Island[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IslandRepository extends AbstractResourceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Island::class);
    }
}