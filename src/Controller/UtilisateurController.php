<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\CheckCodeType;
use App\Form\LoginFormType;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UtilisateurRepository;
use App\Form\UtilisateurType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
        $user->setSalt('abcdef');


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_candidatures');
        }

        return $this->render('utilisateur/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

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
    #[Route('/connexion', name: 'login')]
    public function login(UtilisateurRepository $userRepository,  Request $req, EntityManagerInterface $entityManager): Response
    {
        $error = '';
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            // $data = $form->getData();
            // Récupérer l'utilisateur correspondant à l'e-mail entré
            $user = $userRepository->findOneByEmailAndMdp($form->getData('email'), $form->getData('mdp'));

            // Vérifier si le mot de passe entré correspond à celui stocké dans la base de données
            if ($user != null) {
                //$session->set('user', $user);
                dump('Authentification réussie');

                // Authentification réussie, redirection
                if ($user->getIdrole()->getDescription() == 'Administrateur') {
                    return $this->redirectToRoute('readUsers');
                } else
                    return $this->redirectToRoute('app_candidatures');
            } else {
                $error = 'Adresse e-mail ou mot de passe incorrect';
                dump($error);
            }
        }

        // Afficher le formulaire de connexion avec l'éventuelle erreur
        return $this->render('utilisateur/loginUser.html.twig', ['form' => $form->createView(), 'error' => $error]);
    }
}
