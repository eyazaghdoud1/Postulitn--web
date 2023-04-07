<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CandidaturesRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\OffreRepository;
use App\Entity\Candidatures;
use App\Form\CandidaturesType;


class CandidaturesController extends AbstractController
{
    #[Route('/candidatures', name: 'app_candidatures')]
    public function index(): Response
    {
        return $this->render('candidatures/index.html.twig', [
            'controller_name' => 'CandidaturesController',
        ]);
    }

    /**
     * 
     * add candidature method
     */

    #[Route('/addCandidature', name: 'addCandidature')]
    public function addCandidature(ManagerRegistry $doctrine, Request $request, UtilisateurRepository $userRepo,
     OffreRepository $offreRepo): Response
    {
        $candidature = new Candidatures();
        $candidature->setIdcandidat($userRepo->find(55));
        $candidature->setIdoffre($offreRepo->find(53));
        $form = $this->createForm(CandidaturesType::class, $candidature);
        $form->handleRequest($request); //permet de gerer le traitement
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($candidature); //insert info
            $em->flush(); //update
            return $this->redirectToRoute('readCandidatures');
        } else
            return $this->renderForm('candidatures/addCandidature.html.twig', ['form' => $form]);
    }

    /**
     * 
     * read candidatures method for recruteur
     */

    #[Route('/candidatures', name: 'readCandidatures')]
    public function read(CandidaturesRepository $Rep):Response
    {
        $list = $Rep->findAll();
        //$cvImg= $this->displayPdfAsImage('C:\Users\HP I5\Downloads\CV de graphiste.pdf');
        return $this->render('candidatures/readCandidatures.html.twig', ['list' => $list 
        ]);

    }

    /**
     * 
     * read candidatures for candidat
     */

    #[Route('/candidaturesCand', name: 'candidaturesCand')]
    public function readC(CandidaturesRepository $Rep):Response
    {
        $list = $Rep->findAll();
        //$cvImg= $this->displayPdfAsImage('C:\Users\HP I5\Downloads\CV de graphiste.pdf');
        return $this->render('candidatures/readCandidaturesCandidat.html.twig', ['list' => $list 
        ]);

    }
    /**
     * 
     * update candidature method
     */
    
    #[Route('/updateCandidature/{id}', name: 'updateCandidature')]
    public function updateCandidature(ManagerRegistry $doctrine, Request $request, $id, CandidaturesRepository $repo): Response
    {
        

        $candidature = $repo->find($id);

        $form = $this->createForm(CandidaturesType::class, $candidature);
        //$form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en = $doctrine->getManager();
            $en->flush();

            return $this->redirectToRoute('readCandidatures');
        }

        return $this->render('candidatures/updateCandidature.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * delete candidature method 
     */
    #[Route('/deleteCandidature/{id}', name: 'deleteCandidature')]
    public function delete(CandidaturesRepository $repo, ManagerRegistry $doctrine, $id): Response
    {

        $objet=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute('readCandidatures');

    }
}
