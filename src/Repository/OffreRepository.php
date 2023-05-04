<?php

namespace App\Repository;

use App\Entity\Offre;
use App\Form\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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



    public function findByCriteria($criteria)
    {
        $qb = $this->createQueryBuilder('o');
   
        foreach ($criteria as $key => $value) {
            if (strpos($key, 'like_') === 0) {
                $field = substr($key, 5);
                $qb->andWhere($qb->expr()->like("o.$field", ":$key"))
                    ->setParameter($key, "%$value%");
            } else {
                $qb->andWhere("o.$key = :$key")
                    ->setParameter($key, $value);
            }
        }

        return $qb->getQuery()->getResult();
    }


    
    public function findSearch(SearchData $search)
    
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

public function findSimilarOffers($offer)
{
    return $this->createQueryBuilder('o')
        ->andWhere('o.idtype = :type')
        ->setParameter('type', $offer->getIdtype())
        ->andWhere('o.idoffre != :id')
        ->setParameter('id', $offer->getIdoffre())
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
}

public function findOneByName($name)
{
    return $this->createQueryBuilder('o')
        ->where('o.poste = :nom')
        ->setParameter('nom', $name)
        ->getQuery()
        ->getSingleResult();
}


public function findOneWithType($id)
{
    return $this->createQueryBuilder('o')
        ->select('o', 'o.idoffre,o.specialite,o.dateexpiration,o.poste ,o.description,o.lieu, o.entreprise,t.description AS typeDescription')
        ->join('o.idtype', 't')
        ->where('o.idoffre = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findAllWithType()
{
    return $this->createQueryBuilder('o')
        ->select('o', 't.description AS type_description')
        ->join('o.idtype', 't')
        ->getQuery()
        ->getResult();
}
  //  if ($search->dateExpiration) {
  //      $qb->andWhere('o.dateExpiration = :dateExpiration');
    
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

