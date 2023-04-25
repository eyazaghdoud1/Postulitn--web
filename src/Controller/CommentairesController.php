<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SubmitType; 
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaires; 
use App\Repository\CommentairesRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;
use Symfony\Component\Form\FormTypeInterface;
use App\Form\CommentairesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use App\Repository\ProjetsRepository;


class CommentairesController extends AbstractController
{


    #[Route('/basecom', name: 'app_com')]
    public function index(): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'controller_name' => 'CommentairesController',
        ]);
    }


    #[Route('/ListeCommentaires', name: 'app_commentaires')]
    public function listeCommentaires(CommentairesRepository $repo): Response
    {   $commentaires = $repo->findAll();
        return $this->render('baseback.html.twig', [
            'controller_name' => 'CommentairesController',
            'commentaires'=>$commentaires
        ]);
    }

    #[Route('/addCommentaires', name: 'app_commentaires')]
    public function addCommentaires(Request $req, ManagerRegistry $doctrine)
{
    $commentaires = new Commentaires();
    $form = $this->createForm(CommentairesType::class, $commentaires);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        $idResponsable = $em->getRepository(Utilisateur::class)->find(67); 
       $commentaires->setIdResponsable($idResponsable);
        $em = $doctrine->getManager();
      /*  $commentaires->setIdprojet($idprojet);
        $commentaires->setIdUser($idUser);*/
        $em->persist($commentaires);
        $em->flush();
            return $this->redirectToRoute('app_Commentaires');
    }
    $form = $this->createForm(CommentairesType::class, $commentaires);
    return $this->render('commentaires/addCommentaire.html.twig', [
        'form' => $form->createView(),
    ]);
}

/*

#[Route('/addCommentaire', name: 'app_commentaires')]
public function addCommentaire(Request $request, EntityManagerInterface $entityManager): Response
{
    $commentaires = new Commentaires();
    $form = $this->createForm(CommentairesType::class, $commentaires);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $idUser = $entityManager->getRepository(Utilisateur::class)->find(67); 
        $commentaires->setIduser($this->getIduser());
        $entityManager->persist($commentaires);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentaires');
    }

    return $this->render('commentaires/ShowCom.html.twig', [
        'form' => $form->createView(),
        'commentaires' => $commentaires
    ]);
} 


#[Route('/addCommentaire1', name: 'app_commentaires')]
public function addCommentaire1(Request $request, EntityManagerInterface $entityManager): Response
{
    $commentaires = new Commentaires();
    $form = $this->createForm(CommentairesType::class, $commentaires);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(67);
        $commentaires->setIduser($this->getIduser());
        $entityManager->persist($commentaires);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentaires');
    }

    return $this->render('commentaires/ShowCom.html.twig', [
        'form' => $form->createView(),
        'commentaires' => $commentaires
    ]);
} 





*
     * @Route("/new", name="commentaire_new", methods={"GET","POST"})
     */
    /*
    public function new(Request $request): Response
    {
        $commentaires = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $commentaires->setIdprojet($idprojet);
            $commentaires->setIdUser($iduser);
            $entityManager->persist($commentaires);
            $entityManager->flush();

            return $this->redirectToRoute('app_Commentaires');
        }

        return $this->render('commentaires/addCommentaire.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }

    
*/

#[Route('/addCommentaireP/{idProjet}', name: 'app_commentairesP')]
public function addCommentaireP(Request $request, EntityManagerInterface $entityManager, ProjetsRepository $projetRepository, $idProjet): Response
{
    $commentaire = new Commentaires();
    $projet = $projetRepository->find($idProjet);
    $commentaire->setIdprojet($projet);
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(67);
    $commentaire->setIduser($utilisateur);
    $form = $this->createForm(CommentairesType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentairesP', ['idProjet' => $idProjet]);
    }

    return $this->render('commentaires/ShowCom.html.twig', [
        'form' => $form->createView(),
        'commentaires' => $commentaire,
        'idProjet' => $idProjet,
    ]);
}




    #[Route('/deleteCommentaires/{id}', name: 'delete_Commentaires')]
    public function deleteCommentaires($id,ManagerRegistry $doctrine){
        $commentaires=$doctrine->getRepository(CommentairesType::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute('app_commentaires');
    }

    #[Route('/updateCommentaires/{id}', name: 'update_commentaires')]
    public function updateCommentaires(Request $req,$id,ManagerRegistry $doctrine){
        $commentaires=$doctrine->getRepository(Commentaires::class)->find($id);
        $form = $this->createForm(CommentairesType::class,$commentaires);
        $form->handleRequest($req);

        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_Commentaires');
        }
        
        return $this->render('commentaires/basefront.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    #[Route('/showCommentaires', name: 'show_commentaires')]
    public function show(Commentaires $commentaires): Response
    {
        return $this->render('commentaires/ShowCom.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }



     /**
     * @Route("/", name="commentaire_index", methods={"GET"})
     */

    #[Route('/showCommentaires2', name: 'show_commentaires2')]
    public function index1(CommentairesRepository $CommentairesRepository): Response
    {
        return $this->render('/commentaires/ShowCom.html.twig', [
            'commentaires' => $CommentairesRepository->findAll(),
        ]);
    }

}
