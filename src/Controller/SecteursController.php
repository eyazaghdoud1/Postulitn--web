<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SecteursRepository; 
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Secteurs; 
use App\Form\SecteursType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecteursController extends AbstractController
{
    #[Route('/secteurs', name: 'app_Secteurs')]
    public function index(): Response
    {
        return $this->render('secteurs/baseback.html.twig', [
            'controller_name' => 'SecteursController',
        ]);
    }


    #[Route('/ListeSecteurs', name: 'app_Secteurs')]
    public function listeSecteurs(SecteursRepository $repo): Response
    {   $secteurs = $repo->findAll();
        return $this->render('/secteurs/index.html.twig', [
            'secteurs'=>$secteurs
        ]);
    }


    #[Route('/addSecteurs', name: 'addSecteurs')]
    public function addSecteurs(Request $req,ManagerRegistry $doctrine){
        $secteur = new Secteurs();
        $form = $this->createForm(SecteursType::class,$secteur);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->persist($secteur);
            $em->flush();
            return $this->redirectToRoute('app_Secteurs');
        }
        return $this->render('secteurs/addSecteur.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/updateSecteurs/{id}', name: 'updateSecteurs')]
    public function updateSecteurs(Request $req,$id,ManagerRegistry $doctrine){
        $secteurs=$doctrine->getRepository(Secteurs::class)->find($id);
        $form = $this->createForm(SecteursType::class,$secteurs);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush();
        return $this->redirectToRoute('addSecteurs');
    }
    return $this->render('secteurs/addSecteur.html.twig',[
        'form'=>$form->createView()
    ]);
    }

    #[Route('/deleteSecteurs/{id}', name: 'deleteSecteurs')]
    public function deleteSecteurs($id,ManagerRegistry $doctrine){
        $secteurs=$doctrine->getRepository(Secteurs::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($secteurs);
        $em->flush();
        return $this->redirectToRoute('app_Secteurs');
    }



}
