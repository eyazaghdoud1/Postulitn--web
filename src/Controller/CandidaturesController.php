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
use App\Service\MailerService;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
        OffreRepository $offreRepo,
        FlashyNotifier $flashy, SessionInterface $session
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
            // notif
            $flashy->success('Candidature au poste '.$offreRepo->find(53)->getPoste() .' enregistrée avec succès');
            
            return $this->redirectToRoute('candidaturesCand');
        } else
            return $this->renderForm('candidatures/addCandidature.html.twig', ['form' => $form]);
    
    }

    /**
     * 
     * read candidatures method for recruteur
     */

    //#[Route('/offre/{idoffre}/candidatures', name: 'readCandidatures')]
    #[Route('/candidatures', name: 'readCandidatures')]
    public function read( CandidaturesRepository $Rep, OffreRepository $offreRepo, /*$idoffre*/): Response
    {
        $idoffre = 53; 
        $count = $Rep->numberOfCandidaturePerOffre($idoffre);
        $list = $Rep->findByOffre($idoffre);
       
       
        return $this->render('candidatures/readCandidatures.html.twig', [
            'list' => $list, 'count' => $count, 'offre'=> $offreRepo->find($idoffre)
        ]);
          
    }

    //#[Route('/offre/{idoffre}/candidatures/{etat}', name: 'readCandidatures')]
    #[Route('/candidatures/{etat}', name: 'filterCandidatures')]
    public function filterRec(CandidaturesRepository $Rep, OffreRepository $offreRepo, 
    UtilisateurRepository $userRepo, $etat, /*$idoffre*/): Response
    {
        
        // setting the selected offre
       //$offre = $offreRepo->find($idoffre));
        $offre = $offreRepo->find(53);

        $count = $Rep->numberOfCandidaturePerOffre(53);
        $list = $Rep->filterByEtatOffre($offre,$etat);
        
        return $this->render('candidatures/readCandidatures.html.twig', [
            'list' => $list, 'count' => $count, 'offre'=> $offre
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
     * filter for candidat
     */

    #[Route('/candidaturesCand/{etat}', name: 'filterCandidaturesCand')]
    public function filterCand(CandidaturesRepository $Rep, UtilisateurRepository $userRepo,  $etat): Response
    {
        //$list = $Rep->findAll();
        //setting the connected candidat
        $candidat = $userRepo->find(68);
        $count = $Rep->numberOfCandidaturePerCandidat(68);
       
        $list = $Rep->filterByEtatCandidat($candidat, $etat);
        
        
        return $this->render('candidatures/readCandidaturesCandidat.html.twig', [
            'list' => $list,
            'count' => $count,
            'candidat' => $candidat
        ]);
    }

    /**
     * 
     * update candidature method
     */

    #[Route('/updateCandidature/{id}', name: 'updateCandidature')]
    public function  updateCandidature(ManagerRegistry $doctrine, Request $request,  $id,
     CandidaturesRepository $repo, FlashyNotifier $flashy,): Response
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
            $flashy->success('Candidature modifiée avec succès');
            return $this->redirectToRoute('detailsCandidatureCandidat', ['id'=> $id]);
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
    public function delete(CandidaturesRepository $repo,
     ManagerRegistry $doctrine, $id,
      FlashyNotifier $flashy,
      MailerService $mailer): Response
    {

        $objet = $repo->find($id);
        /* setting the email data */
        $to = $objet->getIdoffre()->getIdrecruteur()->getEmail();
        $subject = 'Candidature annulée';
        $content= 'Le candidat "'.$objet-> getIdcandidat()->getNom(). ' ' .  $objet->getIdcandidat()->getPrenom()
        .'" a annulé sa candidature à votre offre "'.$objet->getIdoffre()->getPoste() ;
        $em = $doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        // notif
        $flashy->info('Votre candidature au poste ' . $objet->getIdoffre()->getPoste().  ' a été supprimée');
       /* sending an email to the candidat */
       $mailer->sendEmail($to, $subject, $content);
        return $this->redirectToRoute('candidaturesCand');
    }
    /**
     * 
     * valider la candidature method
     */

    #[Route('/validerCandidature/{id}', name: 'validerCandidature')]
    public function valider(Candidatures $c,
     ManagerRegistry $doctrine,
      FlashyNotifier $flashy,
       $id,
       MailerService $mailer)
    {
        $c->setEtat('Validée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        // notif
        $flashy->info('Le candidat sera notifié que sa candidature est désormais validée.');
        /* setting the email data */
        $to = $c->getIdcandidat()->getEmail();
        $subject = 'Suivi de vos candidature';
        $content= 'Votre candidature au poste'.$c->getIdoffre()->getPoste().
        'de l\'entreprise '.$c->getIdoffre()->getEntreprise().' a été validée.' ;
       /* sending an email to the candidat */
       $mailer->sendEmail($to, $subject, $content);

        return $this->redirectToRoute('detailsCandidatureRecruteur', ['id' => $id]);
    }

    /**
     * 
     * accepter la candidature
     */
    #[Route('/accepterCandidature/{id}', name: 'accepterCandidature')]
    public function accepter($id, CandidaturesRepository $candRepo,
     ManagerRegistry $doctrine,
      FlashyNotifier $flashy,
      MailerService $mailer)
    {
        $c = $candRepo->find($id);
        $c->setEtat('Acceptée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        //notif
        $flashy->success('Le candidat sera notifié que sa candidature a été acceptée.');
         /* setting the email data */
         $to = $c->getIdcandidat()->getEmail();
         $subject = 'Suivi de vos candidature';
         $content= 'Félicitations! Votre candidature au poste'.$c->getIdoffre()->getPoste().
         'de l\'entreprise '.$c->getIdoffre()->getEntreprise().' a été acceptée.' ;
        /* sending an email to the candidat */
        $mailer->sendEmail($to, $subject, $content);
        return $this->redirectToRoute('readCandidatures');
    }
    /**
     * 
     * refuser la candidature
     */
    #[Route('/refuserCandidature/{id}', name: 'refuserCandidature')]
    public function refuser(CandidaturesRepository $candRepo, $id,
      ManagerRegistry $doctrine,
       FlashyNotifier $flashy, 
       MailerService $mailer)
    {
        $c = $candRepo->find($id);
        $c->setEtat('Refusée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();
        $flashy->warning('Le candidat sera notifié que sa candidature a été refusée.');
        /* setting the email data */
        $to = $c->getIdcandidat()->getEmail();
        $subject = 'Suivi de vos candidature';
        $content= 'Votre candidature au poste'.$c->getIdoffre()->getPoste().
        'de l\'entreprise '.$c->getIdoffre()->getEntreprise().' a été refusée. Bonne continuation.' ;
       /* sending an email to the candidat */
       $mailer->sendEmail($to, $subject, $content);
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
