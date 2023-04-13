<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UtilisateurRepository;
use App\Form\UtilisateurType;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/UsersListe', name: 'readUsers')]
    public function listeUsers(UtilisateurRepository $repo): Response
    {
        $utilisateurs = $repo->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'users' => $utilisateurs
        ]);
    }

    #[Route('/addUser', name: 'signup')]
    public function addUtilisateur(ManagerRegistry $doctrine, Request $req)
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('postuli.tn');
        }

        return $this->render('utilisateur/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /*#[Route('/addAdmin', name: 'addAdmin')]
    public function addUser(Request $req, ManagerRegistry $doctrine)
    {
        $adminuser = new Utilisateur();
        //$adminrole = $doctrine->getManager()->getRepository(Role::class)->find(1);
        $form = $this->createForm(UtilisateurType::class, $adminuser);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $adminuser->setIdrole($em->getRepository(Role::class)->find(1));
            $em->persist($adminuser);
            $em->flush();
            return $this->redirectToRoute('readUsers');
        }
        return $this->render('utilisateur/addAdministrateur.html.twig', [
            'form' => $form->createView()
        ]);
    }*/

    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser($id, ManagerRegistry $doctrine)
    {
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('readUsers');
    }

    #[Route('/updateUser/{id}', name: 'updateUser')]
    public function updateUser(Request $req, $id, ManagerRegistry $doctrine)
    {
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('readUsers');
        }

        return $this->render('utilisateur/updateUtilisateur.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
