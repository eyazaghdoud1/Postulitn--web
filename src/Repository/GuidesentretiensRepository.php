<?php

namespace App\Repository;

use App\Entity\Guidesentretiens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guidesentretiens>
 *
 * @method Guidesentretiens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guidesentretiens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guidesentretiens[]    findAll()
 * @method Guidesentretiens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuidesentretiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guidesentretiens::class);
    }

    public function save(Guidesentretiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Guidesentretiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Guidesentretiens[] Returns an array of Guidesentretiens objects
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

//    public function findOneBySomeField($value): ?Guidesentretiens
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
