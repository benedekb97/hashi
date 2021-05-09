<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GameInterface;
use App\Entity\Island;
use App\Entity\IslandInterface;
use App\Entity\PointInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use http\Exception\RuntimeException;

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

    public function findAllByGame(string $game): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.game', 'game', 'WITH', 'game.id = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }

    public function findByGameIdAndId(string $gameId, string $id): ?IslandInterface
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.game', 'game', 'WITH', 'game.id = :gameId')
            ->andWhere('o.id = :id')
            ->setParameter('gameId', $gameId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNearestToIslandByDirection(IslandInterface $island, string $direction): ?IslandInterface
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->innerJoin('i.point', 'o');

        switch($direction) {
            case IslandInterface::DIRECTION_UP:
                $queryBuilder
                    ->andWhere('o.verticalPosition > :verticalPosition')
                    ->andWhere('o.horizontalPosition = :horizontalPosition')
                    ->orderBy('o.verticalPosition', 'ASC');
                break;
            case IslandInterface::DIRECTION_DOWN:
                $queryBuilder
                    ->andWhere('o.verticalPosition < :verticalPosition')
                    ->andWhere('o.horizontalPosition = :horizontalPosition')
                    ->orderBy('o.verticalPosition', 'DESC');
                break;
            case IslandInterface::DIRECTION_LEFT:
                $queryBuilder
                    ->andWhere('o.verticalPosition = :verticalPosition')
                    ->andWhere('o.horizontalPosition < :horizontalPosition')
                    ->orderBy('o.horizontalPosition', 'DESC');
                break;
            case IslandInterface::DIRECTION_RIGHT:
                $queryBuilder
                    ->andWhere('o.verticalPosition = :verticalPosition')
                    ->andWhere('o.horizontalPosition > :horizontalPosition')
                    ->orderBy('o.horizontalPosition', 'ASC');
                break;
            default:
                throw new RuntimeException(sprintf('Undefined direction: %s', $direction));
        }

        return $queryBuilder
            ->andWhere('i.game = :game')
            ->setParameter('game', $island->getGame())
            ->setParameter('verticalPosition', $island->getPoint()->getVerticalPosition())
            ->setParameter('horizontalPosition', $island->getPoint()->getHorizontalPosition())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}