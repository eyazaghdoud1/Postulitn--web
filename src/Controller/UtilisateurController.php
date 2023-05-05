<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UtilisateurRepository;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function listeUsers(UtilisateurRepository $repo, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
            $utilisateurs = $repo->findAll();
            return $this->render('utilisateur/index.html.twig', [
                'users' => $utilisateurs
            ]);
        } else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/addUser', name: 'signup')]
    public function addUtilisateur(ManagerRegistry $doctrine,  UserPasswordEncoderInterface $userPasswordEncoder, Request $req, SessionInterface $session)
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($req);
        $user->setSalt('abcdef');
        $user->setCode('0000');


        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('mdp')->getData();
            $encodedPassword = $userPasswordEncoder->encodePassword($user, $plainPassword);
            $user->setMdp($encodedPassword);
            /* $user->setMdp(
                $userPasswordEncoder->encodePassword($user, $form->get('mdp')->getData())
            );*/
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
           // return $this->redirectToRoute('login');
           return $this->redirectToRoute('app_comptes_new', ['iduser' => $user->getId(), 'role'=> $user->getRoles()]);
        }

        return $this->render('utilisateur/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser($id, ManagerRegistry $doctrine, FlashyNotifier $flashy, SessionInterface $session)
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
            $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
            $em = $doctrine->getManager();
            $em->remove($utilisateur);
            $em->flush();
            $flashy->primaryDark('User deleted succesfully');
            return $this->redirectToRoute('readUsers');
        } else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/updateUser/{id}', name: 'updateUser')]
    public function updateUser(Request $req, $id, ManagerRegistry $doctrine, FlashyNotifier $flashy, SessionInterface $session)
    {

        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
            $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
            $form = $this->createForm(UtilisateurType::class, $utilisateur);
            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->flush();
                $flashy->primaryDark('User updated succesfully');
                return $this->redirectToRoute('readUsers');
            }

            return $this->render('utilisateur/updateUtilisateur.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('notfound.html.twig');
        }
    }


    #[Route('/connexion', name: 'login')]
    public function login(UtilisateurRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder,  
    Request $req, EntityManagerInterface $entityManager, SessionInterface $session, KernelBrowser $client): Response
    {
        $error = '';
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($req);
        $session = $client->getContainer()->get('session');
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer l'utilisateur correspondant à l'e-mail entré
            $email = $form->get('email')->getData();
            $plainPassword = $form->get('mdp')->getData();
            $user = $userRepository->findOneByEmail($email);

            // Vérifier si le mot de passe entré correspond à celui stocké dans la base de données
            if ($user != null && $userPasswordEncoder->isPasswordValid($user, $plainPassword)) {
                $session->set('user', $user);
                $session->save();
                /* dump($this->session->get('user', $user));
                die();*/
                $cookie = new Cookie($session->getName(), $session->getId());
                $client->getCookieJar()->set($cookie);
                // Authentification réussie, redirection
                if ($session->get('user')->getIdrole()->getDescription() == 'Administrateur') {

                    return $this->redirectToRoute('readUsers');
                } else if ($session->get('user')->getIdrole()->getDescription() == 'Recruteur') {

                    return $this->redirectToRoute('app_offre');
                }
                else {
                    return $this->redirectToRoute('candidaturesCand');
                }
            } else {


                $error = 'Adresse e-mail ou mot de passe incorrect';
                dump($error);
            }
        }

        // Afficher le formulaire de connexion avec l'éventuelle erreur
        return $this->render('utilisateur/loginUser.html.twig', ['form' => $form->createView(), 'error' => $error]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session)
    {
        $session->remove('user');

        // Rediriger l'utilisateur vers la page d'accueil après la déconnexion
        $response = new RedirectResponse('/postuli.tn');
        return $response;
    }
}
