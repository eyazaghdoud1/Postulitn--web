<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntretiensRepository;
use App\Repository\CandidaturesRepository;
use App\Repository\OffreRepository;
use App\Entity\Entretiens;
use App\Form\EntretiensType;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Twilio\Rest\Client;


class EntretiensController extends AbstractController
{
    #[Route('/', name: 'app_entretiens')]
    public function index(): Response
    {
        return $this->render('entretiens/index.html.twig', [
            'controller_name' => 'EntretiensController',
        ]);
    }
    /** calendar */
    #[Route('/entretiens/calendar/{role}/{id}', name: 'entretiensCalendar')]
    public function calendar(EntretiensRepository $repo, $role, $id): Response
    {
        if ($role=='recruteur') {
            $events  = $repo->findByRecruteur($id); 
        } else {
            $events  = $repo->findByCandidat($id); 
        }
        //$events  = $repo->findAll();
        $entretiens = [];
        foreach($events as $event) {
            $entretiens[] = [
                'id'=> $event->getId(),
                'start' => $event->getDate()->format('Y-m-d'), 
                'end' => $event->getDate()->format('Y-m-d'),
                /*'title' => $event->getHeure() . ':' . $event->getIdcandidature()->getIdcandidat()->getNom(). ' ' 
                .  $event->getIdcandidature()->getIdcandidat()->getPrenom(),*/
                'title' => $event->getHeure() . '-' . $event->getType(),
                //'description' => $event->getType(), 
                
                
            ];

        }

        $data = json_encode($entretiens);
        return $this->render('entretiens/calendar.html.twig', array_merge(compact('data'), ['id' => $id, 'role' => $role]));
    }

    #[Route('/calendar/edit/{id}', name: 'editCalendar', methods:'PUT')]
    public function editCalendar(EntretiensRepository $repo,ManagerRegistry $doctrine,  $id, Request $request)
    {
        $donnees = json_decode($request->getContent());
        $entretien = $repo->find($id);
        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) 
           
        ){
           

        $entretien->setDate(new DateTime($donnees->start));
        $em = $doctrine->getManager();
        $em->persist($entretien);
        $em->flush();
        // envoyer un message au candidat
            return new Response('Ok');
            
        }else{
            return new Response('Données incomplètes', 404);
        }
        
        return $this->render('entretiens/calendar.html.twig'
           );

    }
  /************** */

    /**
     * 
     * read entretiens method
     */
    #[Route('/entretiens', name: 'readEntretiens')]
    public function readEntretiens(EntretiensRepository $Rep, UtilisateurRepository $userRepo): Response
    {
        // $list = $Rep->findAll();
        $list = $Rep->findByRecruteur(69);
        $count = $Rep->numberOfEntretiensPerRecruteur(69);
        return $this->render('entretiens/readEntretiens.html.twig', [
            'list' => $list,
            'count' => $count,
            'recruteur' => $userRepo->find(69)
        ]);
    }
    /**
     * 
     * filter for recruteur
     */
    #[Route('/entretiens/{filter}', name: 'filterEntretiensRec')]
    public function filterRec(EntretiensRepository $Rep, UtilisateurRepository $userRepo, $filter): Response
    {

        $list = $Rep->findByRecruteur(69);
        $rec = $userRepo->find(69);
        $count = $Rep->numberOfEntretiensPerRecruteur(69);

        if ($filter == 'today') {
            $list = $Rep->filterByDateForRecruteur($rec, new \DateTime('now'));
         
        } elseif ($filter == 'thismonth') {

            // getting the current month and year
            $month = date('m');
            $year = date('Y');
            $firstdaymonth = date("$year-$month-01");
            $lastdaymonth = date("$year-$month-t", strtotime($firstdaymonth));

            $list = $Rep->plannedEntretiens($rec, $firstdaymonth, $lastdaymonth);
            
        } else if ($filter == 'thisweek') {
            // getting the current week's monday and sunday
            $monday = strtotime('monday this week');
            $sunday = strtotime('sunday this week');

            // converting the dates to Y-m-d format
            $firstdayweek = date('Y-m-d', $monday);
            $lastdayweek = date('Y-m-d', $sunday);

            $list = $Rep->plannedEntretiens($rec, $firstdayweek, $lastdayweek);
            
        }

        $count=count($list);

        return $this->render('entretiens/readEntretiens.html.twig', [
            'list' => $list,
            'count' => $count,
            'recruteur' => $userRepo->find(69)
        ]);
    }
    /**
     * 
     * read entretiens method for candidat
     */
    #[Route('/entretiensCandidat', name: 'entretiensCandidat')]
    public function readEntretiensCandidat(EntretiensRepository $Rep, UtilisateurRepository $userRepo): Response
    {
        // $list = $Rep->findAll();
        $list = $Rep->findByCandidat(68);
        $count = $Rep->numberOfEntretiensPerCandidat(68);
        return $this->render('entretiens/readEntretiensCandidat.html.twig', [
            'list' => $list,
            'count' => $count,
            'candidat' => $userRepo->find(68)
        ]);
    }
    /**
     * 
     * filter for candidat
     */
    
    #[Route('/entretiensCandidat/{filter}', name: 'filterEntretiensCand')]
    public function filterCand(EntretiensRepository $Rep, UtilisateurRepository $userRepo, $filter): Response
    {

        $list = $Rep->findByCandidat(68);
        $cand = $userRepo->find(68);
        $count = $Rep->numberOfEntretiensPerRecruteur(69);

        if ($filter == 'today') {
            $list = $Rep->filterByDateForCandidat($cand, new \DateTime('now'));
         
        } elseif ($filter == 'thismonth') {

            // getting the current month and year
            $month = date('m');
            $year = date('Y');
            $firstdaymonth = date("$year-$month-01");
            $lastdaymonth = date("$year-$month-t", strtotime($firstdaymonth));

            $list = $Rep->plannedEntretiensCand($cand, $firstdaymonth, $lastdaymonth);
            
        } else if ($filter == 'thisweek') {
            // getting the current week's monday and sunday
            $monday = strtotime('monday this week');
            $sunday = strtotime('sunday this week');

            // converting the dates to Y-m-d format
            $firstdayweek = date('Y-m-d', $monday);
            $lastdayweek = date('Y-m-d', $sunday);

            $list = $Rep->plannedEntretiensCand($cand, $firstdayweek, $lastdayweek);
            
        }

        $count=count($list);

        return $this->render('entretiens/readEntretiensCandidat.html.twig', [
            'list' => $list,
            'count' => $count,
            'candidat' => $userRepo->find(68)
        ]);
    }

    /**
     * 
     * read one entretien method
     */
    #[Route('/entretien/{id}', name: 'readEntretien')]
    public function readEntretien(EntretiensRepository $Rep, $id): Response
    {
        $entretien = $Rep->find($id);
        return $this->render('entretiens/readEntretiens.html.twig', [
            'e' => $entretien
        ]);
    }

    /**
     * 
     * add entretien method
     */

    #[Route('/addEntretien/{id}', name: 'addEntretien')]
    public function addEntretien(ManagerRegistry $doctrine, Request $request, CandidaturesRepository $candRepo, $id, FlashyNotifier $flashy): Response
    {
        $entretien = new Entretiens();

        //$entretien->setIdcandidature($candRepo->find(29));
        $entretien->setIdcandidature($candRepo->find($id));
        $form = $this->createForm(EntretiensType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //formatting the time to only save the hours and minutes
            $entretien->setHeure(substr($form->get('heure')->getData(), 0, 5));

            if ($form->get('type')->getData() == "Présentiel") {
                $candRepo->find($id)->setEtat("EntretienPres");
            } else {
                $candRepo->find($id)->setEtat("EntretienTel");
            }
            $em = $doctrine->getManager();
            $em->persist($entretien);
            $em->flush();

            /* sending a message to "candidat" once "recruteur" adds a new meeting  */
            /* using twilio api */
            $account_sid = $this->getParameter('twilio_account_sid');
            $auth_token =  $this->getParameter('twilio_auth_token');
            $twilio_phone_number =  $this->getParameter('twilio_number');
            $receiver_phone_number = '+216' . $entretien->getIdcandidature()->getIdcandidat()->getTel();


            $client = new Client($account_sid, $auth_token);

            $client->messages->create(
                $receiver_phone_number,
                array(
                    "from" => $twilio_phone_number,
                    "body" => "+1"
                    //"body" => "Un entretien pour votre candidature au poste " . $entretien->getIdcandidature()->getIdoffre()->getPoste() . "a été planifié."
                )
            );
            // notif
            $flashy->success('Le candidat a été notifié de l\'ajout de l\'entretien.');
            return $this->redirectToRoute('readEntretiens');
        } else
            return $this->render('entretiens/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * update entretien method
     */

    #[Route('/updateEntretien/{id}', name: 'updateEntretien')]
    public function updateEntretien(ManagerRegistry $doctrine, Request $request, $id, EntretiensRepository $repo): Response
    {

        $entretien = $repo->find($id);

        $entretien->setHeure($entretien->getHeure() . ':00');
        $entretien->setLieu("none");
        $form = $this->createForm(EntretiensType::class, $entretien);
        //$form->add('Modifier', SubmitType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //formatting the time to only save the hours and minutes
            $entretien->setHeure(substr($form->get('heure')->getData(), 0, 5));

            $em = $doctrine->getManager();
            $em->persist($entretien);
            $em->flush();

            /* sending a message to "candidat" once "recruteur" updates a meeting's info  */

            /*$account_sid = $this->getParameter('twilio_account_sid');
            $auth_token =  $this->getParameter('twilio_auth_token');
            $twilio_phone_number =  $this->getParameter('twilio_number');
            $receiver_phone_number = '+21692314270';

            $client = new Client($account_sid, $auth_token);

            $client->messages->create(
                $receiver_phone_number,
                array(
                    "from" => $twilio_phone_number,
                    "body" => "+1"
                    //"body" => "Un entretien pour le poste " . $entretien->getIdcandidature()->getIdoffre()->getPoste() . " a été modifié."
                )
            );
            // notif
            $flashy->success('Le candidat a été notifié de la modification de l\'entretien.');
            */
            return $this->redirectToRoute('readEntretiens');
        }

        return $this->render('entretiens/update.html.twig', ['form' => $form->createView(), 'e' => $entretien]);
    }

    /**
     * 
     * delete entretien method 
     */
    #[Route('/deleteEntretien/{id}', name: 'deleteEntretien')]
    public function deleteEntretien(EntretiensRepository $repo, ManagerRegistry $doctrine, $id): Response
    {

        $objet = $repo->find($id);
        $em = $doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        /* sending a message to "candidat" once "recruteur" deletes a meeting  */

        /*$account_sid = $this->getParameter('twilio_account_sid');
            $auth_token =  $this->getParameter('twilio_auth_token');
            $twilio_phone_number =  $this->getParameter('twilio_number');
            $receiver_phone_number = '+21692314270';

            $client = new Client($account_sid, $auth_token);

            $client->messages->create(
                $receiver_phone_number,
                array(
                    "from" => $twilio_phone_number,
                    "body" => "-1"
                    //"body" => " * L'entretien du " + $entretien->getDate() . 
                     " pour votre candidature au poste " . $entretien->getIdcandidature()->getIdoffre()->getPoste() . " a été annulé."
                )
            );
            // notif
            $flashy->warning('Le candidat a été notifié de l\'annulation de son entretien.');
            */
        return $this->redirectToRoute('readEntretiens');
    }
}
