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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


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
            $data = $form->getData();
            // Récupérer l'utilisateur correspondant à l'e-mail entré
            $user = $userRepository->findOneByEmailAndMdp($form->getData('email'), $form->getData('mdp'));

            // Vérifier si le mot de passe entré correspond à celui stocké dans la base de données
            if ($user != null) {
                //$session->set('user', $user);
                dump('Authentification réussie');

                // Authentification réussie, rediriger l'utilisateur vers la page d'accueil
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


    function sendEmail(MailerInterface $mailer, string $to, string $code, string $message): void
    {
        $email = (new Email())
            ->from('postuli.tn@gmail.com')
            ->to($to)
            ->subject('Mot de passe oublié')
            ->html($code)
            ->text($message);

        $mailer->send($email);
    }

    #[Route('/code', name: 'code')]
    public function checkcode(): Response
    {

        return $this->redirectToRoute('mdp oublié');
    }


    #[Route('/mdpoublie', name: 'mdp oublié')]
    public function forgotmdp(MailerInterface $mailer, Request $req1, Request $req2, UtilisateurRepository $usersRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($req1);
        $form2 = $this->createForm(CheckCodeType::class);
        $form2->handleRequest($req2);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());
            if ($user) {
                $user->setMdp('');
                $entityManager->persist($user);
                $entityManager->flush();
                $code = strval(random_int(1000, 9999));
                $message = '<p>Bonjour ' . $user->getNom() . ' ' . $user->getPrenom() . '</p>'
                    . '<p>Pour votre demande de réinitialisation de mot de passe, veuillez insérer le code suivant : '
                    .  $code . '</p>';
                $this->sendEmail($mailer, $user->getEmail(), $code,  $message);
                return $this->redirectToRoute('code');
                if ($form2->isSubmitted() && $form2->isValid()) {
                    $codeinsere = $form2->get('code')->getData();
                    if ($codeinsere == $code) {
                        return $this->redirectToRoute('login');
                    } else {
                        $error = 'code non valide';
                        dump($error);
                    }
                }
            } else {
                $error = 'Adresse e-mail non valide';
                dump($error);
            }
        }
        return $this->render('utilisateur/forgotpwd.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView()
        ]);
    }



    /*

    #[Route('/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UtilisateurRepository $usersRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            //on verifie si on a un utilisateur
            if ($user) {
                // on genere un token de reinitialisation
                $token = $tokenGenerator->GenerateToken();
                $user->eraseCredentials($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // on genere un lien de reinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // on cree les donnees du mail
                $message = '<p>Bonjour ' . $user->getNom() . ' ' . $user->getPrenom() . '</p>'
                    . '<p>Pour votre demande de réinitialisation de mot de passe, veuillez cliquer sur le lien suivant : '
                    . '<a href="' . $url . '">' . $url . '</a></p>';

                //Envoi du mail
                $this->sendEmail($mailer, $url, $user->getEmail(), $message);


                $this->addFlash('success', 'Email envoyé avec succes');
                return $this->redirectToRoute('login');
            }
            //user est null
            $this->addFlash('danger', 'un probleme est survenu');
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'utilisateur/forgotpwd.html.twig',
            ['requestPassForm' => $form->createView()]
        );
    }
    */
}
