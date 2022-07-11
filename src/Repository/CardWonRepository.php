<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Family;
use App\Entity\CardWon;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CardWon>
 *
 * @method CardWon|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardWon|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardWon[]    findAll()
 * @method CardWon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardWonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardWon::class);
    }

    public function add(CardWon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CardWon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCard(Game $game): array
    {
        return $this->createQueryBuilder('c')
            ->select('identity(c.card)')
            ->where('c.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }

    public function withdrawTheLastCards(int $number, Category $category, Game $game): array
    {
        return $this->createQueryBuilder('cw')
            ->join('cw.card', 'c', 'WITH', 'cw.game=:game')
            ->where('c.category = :category')
            ->setParameter('game', $game)
            ->setParameter('category', $category)
            ->orderBy('cw.id', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }


    public function findByFamily(Family $family, Game $game): array
    {
        return $this->createQueryBuilder('cw')
            ->join('cw.card', 'c', 'WITH', 'cw.game=:game')
            ->where('c.family = :family')
            ->setParameter('game', $game)
            ->setParameter('family', $family)
            ->getQuery()
            ->getResult();
    }

    public function findByOrderFamily(Game $game): array
    {
        return $this->createQueryBuilder('cw')
            ->join('cw.card', 'c', 'WITH', 'cw.game=:game')
            ->setParameter('game', $game)
            ->orderBy('c.family')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return CardWon[] Returns an array of CardWon objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CardWon
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
