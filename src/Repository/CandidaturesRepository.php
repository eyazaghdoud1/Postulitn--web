<?php

namespace App\Repository;

use App\Entity\Candidatures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidatures>
 *
 * @method Candidatures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidatures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidatures[]    findAll()
 * @method Candidatures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidatures::class);
    }

    public function save(Candidatures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    public function remove(Candidatures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * 
     * candidatures par offre
     */
    public function findByOffre($idoffre)
    {
        $querybuilder = $this -> createQueryBuilder('c')
        ->join('c.idoffre', 'o') 
        ->addSelect('o') 
        ->where('c.idoffre= :id')
        ->setParameter('id',$idoffre);
        return $query = $querybuilder->getQuery()->getResult();
    }

     /**
     * 
     * candidatures par candidat
     */
    public function findByCandidat($idcandidat)
    {
        $querybuilder = $this -> createQueryBuilder('c')
        ->join('c.idcandidat', 'u') 
        ->addSelect('u') 
        ->where('u.id= :id')
        ->setParameter('id',$idcandidat);
        return $query = $querybuilder->getQuery()->getResult();
    }

    /**
     * 
     * nombre de candidatures par candidat
     */
    public function numberOfCandidaturePerCandidat($idcandidat) {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT count(c) FROM App\Entity\Candidatures c WHERE  c.idcandidat = :id')->setParameter('id',$idcandidat);;
        return $query->getSingleScalarResult();

    }

    /**
     * 
     * nombre de candidatures par offre
     */
    public function numberOfCandidaturePerOffre($idoffre) {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT count(c) FROM App\Entity\Candidatures c WHERE  c.idoffre = :id')->setParameter('id',$idoffre);;
        return $query->getSingleScalarResult();

    }
     /**
     * 
     * filtrer par état
     */
    public function filterByEtat($etat)
    {
        $querybuilder = $this -> createQueryBuilder('c')
        ->where('c.etat= :etat')
        ->setParameter('etat',$etat);
        return $query = $querybuilder->getQuery()->getResult();
    }
    

//    /**
//     * @return Candidatures[] Returns an array of Candidatures objects
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

//    public function findOneBySomeField($value): ?Candidatures
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
