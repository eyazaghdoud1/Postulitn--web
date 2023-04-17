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
use App\Repository\EntretiensRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


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

    #[Route('/postuler', name: 'addCandidature')]
    public function addCandidature(
        ManagerRegistry $doctrine,
        Request $request,
        UtilisateurRepository $userRepo,
        OffreRepository $offreRepo
    ): Response {
        $candidature = new Candidatures();
        $candidature->setIdcandidat($userRepo->find(55));
        $candidature->setIdoffre($offreRepo->find(53));
        $candidature->setEtat('Enregistrée');
        $candidature->setDate(new \DateTime('now'));
        $form = $this->createForm(CandidaturesType::class, $candidature);
        $form->handleRequest($request); //permet de gerer le traitement
        if ($form->isSubmitted() && $form->isValid()) {
            $filecv = $form->get('cv')->getData();
            $filecv->move("C:\\xampp\\htdocs\\postulitn\\cv", $filecv->getClientOriginalName());
            $candidature->setCv($filecv->getClientOriginalName());
            $filelettre = $form->get('lettre')->getData();
            $filelettre->move("C:\\xampp\\htdocs\\postulitn\\lettres", $filelettre->getClientOriginalName());
            $candidature->setLettre($filelettre->getClientOriginalName());
            $em = $doctrine->getManager();
            $em->persist($candidature); //insert info
            $em->flush(); //update
            return $this->redirectToRoute('candidaturesCand');
        } else
            return $this->renderForm('candidatures/addCandidature.html.twig', ['form' => $form]);
    }

    /**
     * 
     * read candidatures method for recruteur
     */

    #[Route('/candidatures', name: 'readCandidatures')]
    public function read(CandidaturesRepository $Rep, OffreRepository $offreRepo): Response
    {
        //$list = $Rep->findAll();
        $list = $Rep->findByOffre(53);
        $count = $Rep->numberOfCandidaturePerOffre(53);
        
        return $this->render('candidatures/readCandidatures.html.twig', [
            'list' => $list, 'count' => $count, 'offre'=> $offreRepo->find(53)
        ]);
    }

    /**
     * 
     * read candidatures for candidat
     */

    #[Route('/candidaturesCand', name: 'candidaturesCand')]
    public function readC(CandidaturesRepository $Rep, UtilisateurRepository $userRepo): Response
    {
       // $list = $Rep->findAll();
       $list = $Rep->findByCandidat(68);
        $count = $Rep->numberOfCandidaturePerCandidat(68);
        
        
        return $this->render('candidatures/readCandidaturesCandidat.html.twig', [
            'list' => $list,
            'count' => $count,
            'candidat' => $userRepo->find(68)
        ]);
    }
    /**
     * 
     * update candidature method
     */

    #[Route('/updateCandidature/{id}', name: 'updateCandidature')]
    public function  updateCandidature(ManagerRegistry $doctrine, Request $request,  $id, CandidaturesRepository $repo): Response
    {
        $candidature = $repo->find($id);
        if ($request->isMethod('POST')) {
            $filecv = $request->files->get('cv');
            

            $filecv->move("C:\\xampp\\htdocs\\postulitn\\cv", $filecv->getClientOriginalName());
            $candidature->setCv( $request->files->get('cv')->getClientOriginalName());
            $filelettre = $request->files->get('lettre');
            $filelettre->move("C:\\xampp\\htdocs\\postulitn\\lettres", $filelettre->getClientOriginalName());
            $candidature->setLettre($request->files->get('lettre')->getClientOriginalName());
            $em = $doctrine->getManager();
            $em->persist($candidature);
            $em->flush();
            return $this->redirectToRoute('candidaturesCand');
        }
        return $this->render('candidatures/updateCandidature.html.twig', [
         /*   'cv' => $candidature->getCv(),
            'lettre' => $candidature->getLettre()*/
        ]);
    }
    /**
     * 
     * delete candidature method 
     */
    #[Route('/deleteCandidature/{id}', name: 'deleteCandidature')]
    public function delete(CandidaturesRepository $repo, ManagerRegistry $doctrine, $id): Response
    {

        $objet = $repo->find($id);
        $em = $doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute('candidaturesCand');
    }
    /**
     * 
     * valider la candidature method
     */

    #[Route('/validerCandidature/{id}', name: 'validerCandidature')]
    public function valider(Candidatures $c, ManagerRegistry $doctrine)
    {
        $c->setEtat('Validée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        return $this->redirectToRoute('readCandidatures');
    }

    /**
     * 
     * accepter la candidature
     */
    #[Route('/accepterCandidature/{id}', name: 'accepterCandidature')]
    public function accepter($id, CandidaturesRepository $candRepo, ManagerRegistry $doctrine)
    {
        $c = $candRepo->find($id);
        $c->setEtat('Acceptée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        return $this->redirectToRoute('readCandidatures');
    }
    /**
     * 
     * accepter la candidature
     */
    #[Route('/refuserCandidature/{id}', name: 'refuserCandidature')]
    public function refuser(CandidaturesRepository $candRepo, $id,  ManagerRegistry $doctrine)
    {
        $c = $candRepo->find($id);
        $c->setEtat('Refusée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        return $this->redirectToRoute('readCandidatures');
    }

    /**
     * 
     * details candidatures pour recruteur
     */
    #[Route('/detailsCandidatureRecruteur/{id}', name: 'detailsCandidatureRecruteur')]
    public function readDetailsRecruteur(CandidaturesRepository $Rep, EntretiensRepository $entRep, $id): Response
    {
        $c = $Rep->find($id);
        $entretiens = $entRep->filterByCandidature($id);
        return $this->render('candidatures/detailsCandidatureRecruteur.html.twig', [
            'c' => $c, 
            'entretiens'=>$entretiens
        ]);
    }

    /**
     * 
     * details candidatures pour candidat
     */
    #[Route('/detailsCandidatureCandidat/{id}', name: 'detailsCandidatureCandidat')]
    public function readDetailsCandidat(CandidaturesRepository $Rep, EntretiensRepository $entRep, $id): Response
    {
        $c = $Rep->find($id);
        $entretiens = $entRep->filterByCandidature($id);
        return $this->render('candidatures/detailsCandidatureCandidat.html.twig', [
            'c' => $c, 
            'entretiens'=>$entretiens
        ]);
    }
}
