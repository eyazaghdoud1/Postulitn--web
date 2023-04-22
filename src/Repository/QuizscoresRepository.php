<?php

namespace App\Repository;

use App\Entity\Quizscores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizscores>
 *
 * @method Quizscores|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizscores|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizscores[]    findAll()
 * @method Quizscores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizscoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizscores::class);
    }

    public function save(Quizscores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quizscores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findByCandidatAndQuiz($idc, $idq) {
        
        $querybuilder = $this -> createQueryBuilder('qs')
        ->join('qs.idcandidat', 'u') 
        ->join('qs.idquiz', 'q') 
        ->where('qs.idcandidat= :idc')
        ->andWhere('qs.idquiz= :idq')
        ->setParameter('idc', $idc)
        ->setParameter('idq', $idq);
        return $query = $querybuilder->getQuery()->getSingleResult();

    }
//    /**
//     * @return Quizscores[] Returns an array of Quizscores objects
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

//    public function findOneBySomeField($value): ?Quizscores
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
