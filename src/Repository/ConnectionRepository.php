<?php

namespace App\Repository;

use App\Entity\Connection;
use App\Entity\GameInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Connection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Connection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Connection[]    findAll()
 * @method Connection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConnectionRepository extends AbstractResourceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Connection::class);
    }

    public function findAllForGame(GameInterface $game): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.firstIsland', 'firstIsland')
            ->innerJoin('firstIsland.game', 'game', 'WITH', 'game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }
}
