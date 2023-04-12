<?php

namespace App\Repository;

use App\Entity\Entretiens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entretiens>
 *
 * @method Entretiens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entretiens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entretiens[]    findAll()
 * @method Entretiens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntretiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entretiens::class);
    }

    public function save(Entretiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Entretiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Entretiens[] Returns an array of Entretiens objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Entretiens
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

/**
     * 
     * entretiens par candidature
     */
    public function findByOffre($idcandidature)
    {
        $querybuilder = $this -> createQueryBuilder('e')
        ->join('e.idcandidature', 'c') // champ de jointure & alias de l'entité offre
        ->addSelect('c') // select de l'entité jointe classroom
        ->where('c.id= :id')
        ->setParameter('id',$idcandidature);
        return $query = $querybuilder->getQuery()->getResult();
    }

    /**
     * 
     * nombre d'entretiens à venir par candidature
     */
    public function numberOfEntretiensPerCandidature($idcandidature) {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT count(e) FROM App\Entity\Entretiens e WHERE  e.idcandidature = :id')
        ->setParameter('id',$idcandidature);
        return $query->getSingleScalarResult();

    }
    /** 
    * 
    * entretiens par recruteur
    */
   public function findByRecruteur($id)
   {
       $querybuilder = $this -> createQueryBuilder('e')
       ->join('e.idcandidature', 'c') 
       ->join('c.idoffre', 'o') 
       ->join('o.idrecruteur', 'u') 
       ->where('u.id= :id')
       ->setParameter('id',$id);
       return $query = $querybuilder->getQuery()->getResult();
   }
    /**
     * 
     * nombre total des entretiens  par recruteur
     */
    public function numberOfEntretiensPerRecruteur($id) {
        $querybuilder = $this -> createQueryBuilder('e');
        $querybuilder->select('COUNT(e.id)')
        ->join('e.idcandidature', 'c') 
        ->join('c.idoffre', 'o') 
        ->join('o.idrecruteur', 'u') 
        ->where('u.id= :id')
        ->setParameter('id',$id);
        return $query = $querybuilder->getQuery()->getSingleScalarResult();

    }
    /**
     * 
     * nombre des entretiens à venir par recruteur
     */
    public function numberOfPlannedEntretiens($id) {
        $querybuilder = $this -> createQueryBuilder('e');
        $querybuilder->select('COUNT(e.id)')
        ->join('e.idcandidature', 'c') 
        ->join('c.idoffre', 'o') 
        ->join('o.idrecruteur', 'u') 
        ->where('u.id= :id')
        ->AndWhere('e.date > :date')
        ->setParameter('id',$id)
        ->setParameter('date', new \DateTime());
        return $query = $querybuilder->getQuery()->getSingleScalarResult();

    }
    /**
     * 
     * liste des entretiens  à venir par recruteur
     */
    public function plannedEntretiens($id) {
        $querybuilder = $this -> createQueryBuilder('e')
        
        ->join('e.idcandidature', 'c') 
        ->join('c.idoffre', 'o') 
        ->join('o.idrecruteur', 'u') 
        ->where('u.id= :id')
        ->AndWhere('e.date > :date')
        ->setParameter('id',$id)
        ->setParameter('date', new \DateTime());
        return $query = $querybuilder->getQuery()->getResult();

    }
    /**
     * 
     * filtre pour recruteur par date
     */
    public function filterByDateForRecruteur($id, $date) {
        $querybuilder = $this -> createQueryBuilder('e')
        
        ->join('e.idcandidature', 'c') 
        ->join('c.idoffre', 'o') 
        ->join('o.idrecruteur', 'u') 
        ->where('u.id= :id')
        ->andWhere('e.date = :date')
        ->orderBy('e.heure', 'DESC')
        ->setParameter('id',$id)
        ->setParameter('date', $date);
        return $query = $querybuilder->getQuery()->getResult();

    }
    /**
     * 
     * filtre pour candidat par date
     */
    public function filterByDateForCandidat($id, $date) {
        $querybuilder = $this -> createQueryBuilder('e')
        
        ->join('e.idcandidature', 'c') 
        ->where('c.idcandidat= :id')
        ->andWhere('e.date = :date')
        ->orderBy('e.heure', 'DESC')
        ->setParameter('id',$id)
        ->setParameter('date', $date);
        
        return $query = $querybuilder->getQuery()->getResult();

    }
    /**
     * 
     * filtre pour candidat par candidature
     */
    public function filterByCandidature($id) {
        $querybuilder = $this -> createQueryBuilder('e')
        ->where('e.idcandidature= :id')
        ->setParameter('id',$id)
        ;
        
        return $query = $querybuilder->getQuery()->getResult();

    }

}
