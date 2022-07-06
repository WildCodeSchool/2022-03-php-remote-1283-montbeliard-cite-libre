<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function add(Card $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Card $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function selectRandomByLevel(int $level, int $gameId): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->leftJoin('c.card_won', 'cw', 'WITH', 'cw.game=:gameId')
    //         ->addSelect('RAND() as HIDDEN rand')
    //         ->where('c.id !== cw.id')
    //         ->setParameter(':level', $level)
    //         ->setParameter(':gameId', $gameId)
    //         ->orderBy('rand')
    //         ->setMaxResults(':level')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function selectRandomByNumber(int $number): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('RAND() as HIDDEN rand')
            // ->setParameter(':number', $number)
            ->orderBy('rand')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Card[] Returns an array of Card objects
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

//    public function findOneBySomeField($value): ?Card
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
