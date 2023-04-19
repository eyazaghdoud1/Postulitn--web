<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProjetsType;
use App\Entity\Projets;
use App\Repository\ProjetsRepository;
use App\Repository\Commentairesepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormTypeInterface;

class ProjetsController extends AbstractController
{
    #[Route('/base', name: 'app_projets')]
    public function index(): Response
    {
        return $this->render('/base.html.twig', [
            'controller_name' => 'ProjetsController',
        ]);
    }

    
    #[Route('/ListeProjetsRecruteur', name: 'app_projets2')]
    public function ListeProjets(ProjetsRepository $repo): Response
    {   $projets = $repo->findAll();
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

    #[Route('/addProjets', name: 'addProjets')]
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
    public function readDetailsCandidat(ProjetsRepository $Rep, $id): Response
    {
        $p = $Rep->find($id);
        return $this->render('projets/ProjetsDetailsCandidat.html.twig', [
            'p' => $p, 
        ]);
    }
    #[Route('/detailsProjetRecruteur/{id}', name: 'detailsProjetRecruteur')]
    public function readDetailsRecruteur(ProjetsRepository $Rep, $id): Response
    {
        $p = $Rep->find($id);
        return $this->render('projets/ProjetsDetailsRecruteur.html.twig', [
            'p' => $p, 
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

