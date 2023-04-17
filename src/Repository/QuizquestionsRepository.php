<?php

namespace App\Repository;

use App\Entity\Quizquestions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizquestions>
 *
 * @method Quizquestions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizquestions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizquestions[]    findAll()
 * @method Quizquestions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizquestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizquestions::class);
    }

    public function save(Quizquestions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quizquestions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Quizquestions[] Returns an array of Quizquestions objects
//     */

    public function findByQuiz($idquiz): array
    {
        return $this->createQueryBuilder('q')
            ->where('q.idquiz = :quiz')
            ->setParameter('quiz', $idquiz)
            ->getQuery()
           ->getResult()
       ;
    }

//    public function findOneBySomeField($value): ?Quizquestions
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
