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
use Consoletvs\Profanity\Checker;
use Consoletvs\Profanity\Profanity;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class CommentairesController extends AbstractController
{


    #[Route('/basecom', name: 'app_com')]
    public function index(): Response
    {

    $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findAll();
    return $this->render('commentaires/index.html.twig', [
        'commentaires' => $commentaires
    ]);
    }
    
  #[Route('/listCommentairesP/{idprojet}', name: 'app_commentaires')]
public function listeCommentaires(CommentairesRepository $repo, $idprojet): Response
{
    $queryBuilder = $repo->createQueryBuilder('c')
        
        ->where('c.idprojet = :idprojet')
       
        ->setParameter('idprojet', $idprojet)
        ->getQuery();

    $commentaires = $queryBuilder->getResult();
    

    return $this->render('/commentaires/ListeCom.html.twig', [
        'controller_name' => 'CommentairesController',
        'commentaires' => $commentaires,
    ]);
}


  /*  #[Route('/addCommentaires', name: 'app_commentaires')]
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
        $commentaires->setIdUser($idUser);
        $em->persist($commentaires);
        $em->flush();
            return $this->redirectToRoute('app_Commentaires');
    }
    $form = $this->createForm(CommentairesType::class, $commentaires);
    return $this->render('commentaires/addCommentaire.html.twig', [
        'form' => $form->createView(),
    ]);
}**************************************************************************************/


#[Route('/addCommentaireP/{idProjet}', name: 'app_commentairesP')]
public function addCommentaireP(Request $request, EntityManagerInterface $entityManager, ProjetsRepository $projetRepository, SessionInterface $session,CommentairesRepository $repo, $idProjet): Response
{
    $queryBuilder = $repo->createQueryBuilder('c')  
    ->join('c.iduser', 'u')
    ->where('c.idprojet = :idProjet')
    ->setParameter('idProjet', $idProjet)
    ->getQuery();

$commentaires = $queryBuilder->getResult();
    $commentaire = new Commentaires();
    $projet = $projetRepository->find($idProjet);
    $commentaire->setIdprojet($projet);
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(67);
    $commentaire->setIduser($utilisateur);
    $form = $this->createForm(CommentairesType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Filter bad words
         // Get the content of the submitted comment
        $commentaireContent = $commentaire->getContenu();
        $badWords = ['bad_word_1', 'bad_word_2', 'bad_word_3'];
        // Define an array of bad words to be filtered
        $filteredContent = str_ireplace($badWords, '****', $commentaireContent);
        // Use the str_ireplace function to replace any bad words in the submitted comment with asterisks
        $commentaire->setContenu($filteredContent);
        // Set the filtered comment content in the Commentaires object
        $entityManager->persist($commentaire);
        $entityManager->flush();
        // Add flash message to notify the user
        $session->getFlashBag()->add('success', 'Comment added successfully!');

        return $this->redirectToRoute('app_commentairesP', ['idProjet' => $idProjet]);
    }

    return $this->render('commentaires/ShowCom.html.twig', [
        'form' => $form->createView(),
        'commentaires' => $commentaires,
        'idProjet' => $idProjet,

    ]);
}

/*
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
        // Filter bad words
        $commentaireContent = $commentaire->getContenu();
        $filteredContent = Profanity::filter($commentaireContent);
        $commentaire->setContenu($filteredContent);
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentairesP', ['idProjet' => $idProjet]);
    }

    return $this->render('commentaires/ShowCom.html.twig', [
        'form' => $form->createView(),
        'commentaires' => $commentaire,
        'idProjet' => $idProjet,
    ]);
}*/



/*

#[Route('/addCommentaireP/{idProjet}', name: 'app_commentairesP')]
public function addCommentaireP1(Request $request, EntityManagerInterface $entityManager, ProjetsRepository $projetRepository, $idProjet): Response
{
    $commentaire = new Commentaires();
    $projet = $projetRepository->find($idProjet);
    $commentaire->setIdprojet($projet);
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(67);
    $commentaire->setIduser($utilisateur);
    $form = $this->createForm(CommentairesType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Filter bad words
        $commentaireContent = $commentaire->getContenu();
        $filteredContent = Profanity::blocker()->block($commentaireContent);
        $commentaire->setContenu($filteredContent);
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
*/


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

}
