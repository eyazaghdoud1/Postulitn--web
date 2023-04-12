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
    public function readEntretiens(EntretiensRepository $Rep):Response
    {
       // $list = $Rep->findAll();
       $list = $Rep->findByRecruteur(69);
       $count = $Rep->numberOfEntretiensPerRecruteur(69);
        return $this->render('entretiens/readEntretiens.html.twig', ['list' => $list, 'count'=>$count
        ]);

    }

     /**
     * 
     * read one entretien method
     */
    #[Route('/entretien/{id}', name: 'readEntretien')]
    public function readEntretien(EntretiensRepository $Rep, $id):Response
    {
        $entretien = $Rep->find($id);
        return $this->render('entretiens/readEntretiens.html.twig', ['e' => $entretien
        ]);

    }

     /**
     * 
     * add entretien method
     */

    #[Route('/addEntretien', name: 'addEntretien')]
    public function addEntretien(ManagerRegistry $doctrine, Request $request, CandidaturesRepository $candRepo): Response
    {
        $entretien = new Entretiens();
       
        $entretien->setIdcandidature($candRepo->find(29));
        //$entretien->setIdcandidature($candRepo->find($id));
        $form = $this->createForm(EntretiensType::class, $entretien);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            //formatting the time to only save the hours and minutes
            $entretien->setHeure(substr($form->get('heure')->getData(),0,5));
            $em = $doctrine->getManager();
            $em->persist($entretien); 
            $em->flush(); 
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

        $entretien->setHeure($entretien->getHeure().':00');
        $form = $this->createForm(EntretiensType::class, $entretien);
        //$form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $en = $doctrine->getManager();
            $en->flush();

            return $this->redirectToRoute('readEntretiens');
        }

        return $this->render('entretiens/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * delete entretien method 
     */
    #[Route('/deleteEntretien/{id}', name: 'deleteEntretien')]
    public function deleteEntretien(EntretiensRepository $repo, ManagerRegistry $doctrine, $id): Response
    {

        $objet=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute('readEntretiens');

    }
}
