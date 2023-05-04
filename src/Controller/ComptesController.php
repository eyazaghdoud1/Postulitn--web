<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Form\Comptes1Type;
use App\Repository\ComptesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/comptes')]
class ComptesController extends AbstractController
{
    #[Route('/listeCompte', name: 'app_comptes_index', methods: ['GET'])]
    public function index(ComptesRepository $comptesRepository): Response
    {
        return $this->render('comptes/index.html.twig', [
            'comptes' => $comptesRepository->findAll(),
        ]);
    }

    #[Route('/new/{iduser}/{role}', name: 'app_comptes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComptesRepository $comptesRepository,
     $iduser, UtilisateurRepository $userRepo, ManagerRegistry $doctrine,
     $role): Response
    {
        $compte = new Comptes();
        $user = $userRepo->find($iduser);
        $compte->setIdutilisateur($user);
      
        $form = $this->createForm(Comptes1Type::class, $compte);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
         
            $em=$doctrine->getManager();
            $em->persist($compte);
            $em->flush();
         
    
            //$comptesRepository->save($compte, true);

            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptes/new.html.twig', [
            'compte' => $compte,
            'form' => $form,
            'role' => $role
        ]);
    }

  /*  #[Route('/new', name: 'app_comptes_new', methods: ['GET', 'POST'])]
    public function new1(Request $request, ComptesRepository $comptesRepository): Response
    {
        $compte = new Comptes();
        $form = $this->createForm(Comptes1Type::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptesRepository->save($compte, true);

            return $this->redirectToRoute('app_comptes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptes/new.html.twig', [
            'compte' => $compte,
            'form' => $form,
        ]);
    } */

    #[Route('/showCompteConnecte/{idcompte}', name: 'app_comptes_show1', methods: ['GET'])]
    public function show($idcompte, ComptesRepository $crepo): Response
    {
        $c= $crepo->find($idcompte);
        return $this->render('comptes/show.html.twig', [
            'compte' => $c,
        ]);
    }

    
    #[Route('/showCompteRec/{idcompte}', name: 'app_comptes_show2', methods: ['GET'])]
    public function showR(Comptes $compte): Response
    {
        return $this->render('comptes/ShowCompteRec.html.twig', [
            'compte' => $compte,
        ]);
    }


    #[Route('/showCompteBack/{idcompte}', name: 'app_comptes_show3', methods: ['GET'])]
    public function showBack(Comptes $compte): Response
    {
        return $this->render('comptes/ShowCompteBack.html.twig', [
            'compte' => $compte,
        ]);
    }

    #[Route('/{idcompte}/edit', name: 'app_comptes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comptes $compte, ComptesRepository $comptesRepository): Response
    {
        $form = $this->createForm(Comptes1Type::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptesRepository->save($compte, true);

            return $this->redirectToRoute('app_comptes_show1', ['idcompte' => $compte->getIdcompte()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptes/edit.html.twig', [
            'compte' => $compte,
            'form' => $form,
        ]);
    }


    #[Route('/{idcompte}', name: 'app_comptes_delete', methods: ['POST'])]
    public function delete(Request $request, Comptes $compte, ComptesRepository $comptesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compte->getIdcompte(), $request->request->get('_token'))) {
            $comptesRepository->remove($compte, true);
        }

        return $this->redirectToRoute('app_comptes_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{idcompte}/topdf', name: 'app_comptes_pdf', methods: ['GET'])]
    public function pdfExport(Pdf $snappy,Comptes $compte,ComptesRepository $comptesRepository) :Response {
        $html = $this->renderView('comptes/_pdf.html.twig', [
            'compte' => $compte,

        ]);
      
        $snappy->setOption('enable-local-file-access', true);
        $snappy->setOption('orientation','landscape');
        $pdf = $snappy->getOutputFromHtml($html);

        return new PdfResponse(
            $pdf,
            'resume.pdf'
        );
    }
    
    

}
