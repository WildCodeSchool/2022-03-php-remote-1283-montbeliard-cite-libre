<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByScore(): array
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.score')
            ->getQuery()
            ->getResult();
    }

    public function findLikeKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.classe', 'c')
            ->leftJoin('g.user', 'u')
            ->where('u.username LIKE :keyword')
            ->orWhere('c.classe LIKE :keyword')
            ->orWhere('g.name LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('g.actualDuration')
            ->getQuery()
            ->getResult();
    }

    public function findByUser(): array
    {
        return $this->createQueryBuilder('g')
            ->leftjoin('g.user', 'u')
            ->orderBy('u.username')
            ->getQuery()
            ->getResult();
    }

    // public function findByTime(): array
    // {
    //     return $this->createQueryBuilder('g')
    //         ->orderBy('g.actualDuration')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findByClasse(): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.classe', 'c')
            ->orderBy('c.classe')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Game
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
