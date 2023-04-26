<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\DateType;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function save(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findSearch(SearchData $search): QueryBuilder
{
    $qb = $this->createQueryBuilder('o');

    if ($search->poste) {
        $qb->andWhere('o.poste LIKE :poste')
            ->setParameter('poste', '%' . $search->poste . '%');
    }

    if ($search->lieu) {
        $qb->andWhere('o.lieu LIKE :lieu')
            ->setParameter('lieu', '%' . $search->lieu . '%');
    }
}
    public function searchByKeywords($keywords)
{
    $query = $this->createQueryBuilder('o')
        ->where('o.poste LIKE :keywords OR o.lieu LIKE :keywords')
        ->setParameter('keywords', '%'.$keywords.'%')
        ->getQuery();
    
    return $query->getResult();
}
   // if ($search->dateExpiration) {
     //   $qb->andWhere('o.dateExpiration = :dateExpiration');
    
//}


//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function countByDate(){
    // $query = $this->createQueryBuilder('a')
    //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
    //     ->groupBy('dateAnnonces')
    // ;
    // return $query->getQuery()->getResult();
    $query = $this->getEntityManager()->createQuery("
        SELECT SUBSTRING(a.dateexpiration, 1, 10) as dateExpiration, COUNT(a) as count FROM App\Entity\Offre a GROUP BY dateExpiration
    ");
    return $query->getResult();
}
}

