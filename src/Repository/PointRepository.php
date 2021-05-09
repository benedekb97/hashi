<?php

namespace App\Repository;

use App\Entity\Point;
use App\Entity\PointInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Point|null find($id, $lockMode = null, $lockVersion = null)
 * @method Point|null findOneBy(array $criteria, array $orderBy = null)
 * @method Point[]    findAll()
 * @method Point[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointRepository extends AbstractResourceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Point::class);
    }

    public function findByPosition(int $horizontalPosition, int $verticalPosition): ?PointInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.verticalPosition = :verticalPosition')
            ->andWhere('o.horizontalPosition = :horizontalPosition')
            ->setParameter('verticalPosition', $verticalPosition)
            ->setParameter('horizontalPosition', $horizontalPosition)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
