<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Entity\Commentaires;
use App\Entity\Offre;
use App\Entity\Typeoffre;
//use App\Controller\Typeoffre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProjetsType;
use App\Entity\Projets;
use App\Repository\ProjetsRepository;
use App\Repository\OffreRepository;
use App\Repository\TypeoffreRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\Commentairesepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twilio\Rest\Proxy\V1\Service\SessionInstance;

class ProjetsController extends AbstractController
{
    #[Route('/base', name: 'app_projets')]
    public function index(): Response
    {
        return $this->render('/base.html.twig', [
            'controller_name' => 'ProjetsController',
        ]);
    }

    #[Route('/ListeProjetsCurrentUser', name: 'app_projets3')]
    public function ListeProjetsCurrent(ProjetsRepository $repo, SessionInterface $session): Response
    {    $projets = $repo->findByRespo($session->get('user')->getId());
        return $this->render('/projets/index.html.twig', [
            'controller_name' => 'ProjetsController',
            'projets'=>$projets
        ]);

/*
        $queryBuilder = $repo->createQueryBuilder('c') 
        ->where('c.idprojet = :idprojet')   
        ->setParameter('idprojet', $idprojet)
        ->getQuery();

    $commentaires = $queryBuilder->getResult();*/
    /*    return $this->render('/projets/ListeProjetCurrent.html.twig', [
            'controller_name' => 'ProjetsController',
            'projets'=>$projets
        ]);*/
    }




    #[Route('/ListeProjetsRecruteur', name: 'app_projets2')]
    public function ListeProjets(ProjetsRepository $repo, SessionInterface $session): Response
    {   $projets = $repo->findByRespo($session->get('user')->getId());
        return $this->render('/projets/index.html.twig', [
            'controller_name' => 'ProjetsController',
            'projets'=>$projets
        ]);
    }

    #[Route('/ListeProjetsCandidat', name: 'app_projets1')]
    public function ListeProjets1(ProjetsRepository $repo): Response
    {   $projets = $repo->findAll();
       
        return $this->render('/projets/ListingProjets.html.twig', [
            'projets'=>$projets
        ]);
    }

   /* #[Route('/addProjets', name: 'addProjets')]
    public function addProjets(Request $req,ManagerRegistry $doctrine){
        $projets = new Projets();
        //$idresponsabla = setIdResponsable(id);
        $form = $this->createForm(ProjetsType::class,$projets);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $idResponsable = $em->getRepository(Utilisateur::class)->find(67); 
            $projets->setIdResponsable($idResponsable);
            $em->persist($projets);
            $em->flush();
            return $this->redirectToRoute('app_projets2');
        }
        return $this->render('/projets/addProjet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/


#[Route('/addProjets', name: 'addProjets')]
//#[Route('/addProjets/{iduser}', name: 'addProjets')]
public function addProjets(Request $req,ManagerRegistry $doctrine,OffreRepository $offrerepo,
TypeoffreRepository $torepo, UtilisateurRepository $userrepo, SessionInterface $session)
{
    $projets = new Projets();   
    //$idresponsabla = setIdResponsable(id);
    $form = $this->createForm(ProjetsType::class,$projets);
    $form->handleRequest($req);
    if($form->isSubmitted() && $form->isValid()){

        
        $em=$doctrine->getManager();
        $idResponsable = $em->getRepository(Utilisateur::class)->find($session->get('user')->getId()); 
        $projets->setIdResponsable($idResponsable);
        $em->persist($projets);
        $em->flush();
       
        $this->addoffre($doctrine, $em->getRepository(Projets::class)->find($projets->getIdprojet()),
         $torepo, $userrepo,
         $session->get('user')->getId());
    
    
    return $this->redirectToRoute('app_projets2');
}
    return $this->render('/projets/addProjet.html.twig', [
        'form' => $form->createView()
    ]);
}


public function addoffre( ManagerRegistry $doctrine1,
 Projets $p,
 TypeoffreRepository $torepo, UtilisateurRepository $userrepo, $id  ) {
// Create new Offre entity and set its attributes statically
$offre = new Offre();
$offre->setPoste($p->getNom());
$offre->setDescription($p->getDescription());
$offre->setDateexpiration($p->getDatefin());
$offre->setLieu('none ');
$offre->setEntreprise('none');
$offre->setSpecialite('none');
$offre->setIdtype($torepo->findOneByDesc('ProjetFreelance')); 
$offre->setIdrecruteur($userrepo->find($id));
 // Persist and flush the entity
$em1=$doctrine1->getManager();
$em1->persist($offre);
$em1->flush();
}



    #[Route('/deleteProjets/{id}', name: 'delete_Projets')]
    public function deleteProjets($id,ManagerRegistry $doctrine){
        $projets=$doctrine->getRepository(Projets::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($projets);
        $em->flush();
        return $this->redirectToRoute('app_projets2');
    }

    #[Route('/updateProjets/{id}', name: 'update_Projets')]
    public function updateProjets(Request $req,$id,ManagerRegistry $doctrine){
        $projets=$doctrine->getRepository(Projets::class)->find($id);
        $form = $this->createForm(ProjetsType::class,$projets);
        $form->handleRequest($req);

        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_projets2');
        }
        return $this->render('/projets/addProjet.html.twig',[
            'form'=>$form->createView()
        ]);
    }
   
    #[Route('/detailsProjetCandidat/{id}', name: 'detailsProjetCandidat')]
    public function readDetailsCandidat(ProjetsRepository $Rep, $id, OffreRepository $offrerepo): Response
    {
        $p = $Rep->find($id);
        $o = $offrerepo->findOneByName($p->getNom());
        return $this->render('projets/ProjetsDetailsCandidat.html.twig', [
            'p' => $p,
            'o'=> $o, 
        ]);
    }
    #[Route('/detailsProjetRecruteur/{id}', name: 'detailsProjetRecruteur')]
    public function readDetailsRecruteur(ProjetsRepository $Rep, $id, OffreRepository $offrerepo): Response
    {
        $p = $Rep->find($id);
 $o = $offrerepo->findOneByName($p->getNom());
        return $this->render('projets/ProjetsDetailsRecruteur.html.twig', [
            'p' => $p, 
            'o'=> $o,
        ]);
    }

   /* #[Route('/showCommentaires2', name: 'show_commentaires2')]
    public function index1(CommentairesRepository $CommentairesRepository): Response
    {
        return $this->render('projets/ProjetsDetailsCandidat.html.twig', [
            'commentaires' => $CommentairesRepository->findAll(),
        ]);
    }
/*/
}

