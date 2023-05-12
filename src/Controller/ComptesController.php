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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twilio\Rest\Proxy\V1\Service\SessionInstance;

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
            $file = $form->get('photo')->getData();
            $file->move("C:\\xampp\\htdocs\\postulitn\\images", $file->getClientOriginalName());
            $compte->setPhoto($file->getClientOriginalName());
         
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

    #[Route('/showCompteConnecte', name: 'app_comptes_show1', methods: ['GET'])]
    public function show(UtilisateurRepository $userrepo, ComptesRepository $crepo, SessionInterface $session): Response
    {

       // $userrepo->find($session->get('user')->getId());

        $c= $crepo->findByUser($session->get('user')->getId());
        return $this->render('comptes/show.html.twig', [
            'compte' => $c,
        ]);
    }

    
    #[Route('/showCompteRec/{idcandidat}', name: 'app_comptes_show2', methods: ['GET'])]
    public function showR(ComptesRepository $crepo, $idcandidat): Response
    {
        $c= $crepo->findByUser($idcandidat);
        return $this->render('comptes/ShowCompteRec.html.twig', [
            'compte' => $c,
        ]);
    }


    #[Route('/showCompteBack/{idcompte}', name: 'app_comptes_show3', methods: ['GET'])]
    public function showBack(Comptes $compte): Response
    {
        return $this->render('comptes/ShowCompteBack.html.twig', [
            'compte' => $compte,
        ]);
    }

   /* #[Route('/{idcompte}/edit', name: 'app_comptes_edit', methods: ['GET', 'POST'])]
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
    }*/
    #[Route('/{idcompte}/edit', name: 'app_comptes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ComptesRepository $comptesRepository, $idcompte): Response
    {
        $compte=$comptesRepository->find($idcompte);
        if ($request->isMethod('POST')) {
            $file = $request->files->get('photo');
            $file->move("C:\\xampp\\htdocs\\postulitn\\images", $file->getClientOriginalName());
            $compte->setPhoto($file->getClientOriginalName());
            $compte->setDiplome($request->request->get('diplome'));
            $compte->setDatediplome($request->request->get('datediplome'));
            $compte->setEntreprise($request->request->get('entreprise'));
            $compte->setDomaine($request->request->get('domaine'));
            $compte->setPoste($request->request->get('poste'));
            $compte->setExperience($request->request->get('experience'));

            $comptesRepository->save($compte, true);

            return $this->redirectToRoute('app_comptes_show1', ['idcompte' => $compte->getIdcompte()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptes/edit.html.twig', [
            'compte' => $compte,
           
        ]);
    }
    #[Route('delete/{idcompte}', name: 'deleteCompte')]
    public function deletecompte(ManagerRegistry $doctrine,$idcompte,
     ComptesRepository $comptesRepository, UtilisateurRepository $userrepo): Response
    {
       $compte = $comptesRepository->find($idcompte);
       $user= $userrepo->find($compte->getIdutilisateur()->getId());
        $em = $doctrine->getManager();
        $em->remove( $compte);
        $em->remove($user); 
        $em->flush();
           
        

        return $this->redirectToRoute('app_candidatures');
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
