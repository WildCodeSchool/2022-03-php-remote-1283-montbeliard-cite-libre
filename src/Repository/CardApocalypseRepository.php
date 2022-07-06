<?php

namespace App\Repository;

use App\Entity\CardApocalypse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CardApocalypse>
 *
 * @method CardApocalypse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardApocalypse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardApocalypse[]    findAll()
 * @method CardApocalypse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardApocalypseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardApocalypse::class);
    }

    public function add(CardApocalypse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CardApocalypse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function selectRandom(): array
    {
        return $this->createQueryBuilder('ca')
            ->leftJoin('ca.cardWons', 'cw')
            ->addSelect('RAND() as HIDDEN rand')
            ->where('cw.cardApocalypse IS NULL')
            ->orderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return CardApocalypse[] Returns an array of CardApocalypse objects
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

    //    public function findOneBySomeField($value): ?CardApocalypse
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
