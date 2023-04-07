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
        $list = $Rep->findAll();
        return $this->render('entretiens/readEntretiens.html.twig', ['list' => $list
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

    #[Route('/addEntretien/{id}', name: 'addEntretien')]
    public function addEntretien(ManagerRegistry $doctrine, Request $request, EntretiensRepository $candRepo, $id): Response
    {
        $entretien = new Entretiens();
        //$entretien->setIdcandidat($userRepo->find(55));
        $entretien->setIdcandidature($candRepo->find($id));
        $form = $this->createForm(EntretiensType::class, $entretien);
        $form->handleRequest($request); //permet de gerer le traitement
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($entretien); //insert info
            $em->flush(); //update
            return $this->redirectToRoute('readEntretiens');
        } else
            return $this->renderForm('entretiens/addEntretien.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * update entretien method
     */
    
    #[Route('/updateEntretien/{id}', name: 'updateEntretien')]
    public function updateCandidature(ManagerRegistry $doctrine, Request $request, $id, EntretiensRepository $repo): Response
    {

        $entretien = $repo->find($id);

        $form = $this->createForm(EntretiensType::class, $entretien);
        //$form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en = $doctrine->getManager();
            $en->flush();

            return $this->redirectToRoute('readEntretiens');
        }

        return $this->render('entretiens/updateEntretiens.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * delete entretien method 
     */
    #[Route('/deleteEntretien/{id}', name: 'deleteEntretien')]
    public function delete(EntretiensRepository $repo, ManagerRegistry $doctrine, $id): Response
    {

        $objet=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute('readEntretiens');

    }
}
