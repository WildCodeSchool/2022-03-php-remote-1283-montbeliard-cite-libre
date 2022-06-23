<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\QuestionAsked;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function add(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function selectRandomByLevel(int $level)
    {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.questionAskeds', 'qa')
            ->addSelect('RAND() as HIDDEN rand')
            ->where('q.level = :level')
            ->setParameter('level', $level)
            ->orderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        /* $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare(
            'SELECT *
            FROM question q
            LEFT JOIN question_asked qa
            ON q.id = qa.question_id
            WHERE q.level = :level
            AND qa.question_id IS NULL
            ORDER BY RAND() LIMIT 0,1'
        );
        $statement->bindValue('level', $level);

        return $statement
            ->executeQuery()
            ->fetchOne(); */
    }


    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
