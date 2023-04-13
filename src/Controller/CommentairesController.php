<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentairesController extends AbstractController
{
    #[Route('/commentaires', name: 'app_commentaires')]
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


    #[Route('/addCommentaires', name: 'add_commentaires')]
    public function addCommentaires(Request $req,ManagerRegistry $doctrine){
        $commentaires = new Commentaires();
        $form = $this->createForm(Commentaires::class,$commentaires);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->persist($commentaires);
            $em->flush();
            return $this->redirectToRoute('app_Commentaires');
        }
        return $this->render('Commentaires/basefront.html.twig',[
            'form'=>$form->createView()
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
}
