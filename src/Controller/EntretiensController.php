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
    public function addEntretien(ManagerRegistry $doctrine, Request $request, CandidaturesRepository $candRepo, $id): Response
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
            */
        return $this->redirectToRoute('readEntretiens');
    }
}
