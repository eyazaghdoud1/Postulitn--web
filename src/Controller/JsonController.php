<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\CheckCodeType;
use App\Form\LoginFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\UtilisateurType;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class JsonController extends AbstractController
{


    #[Route('/json', name: 'app_json')]
    public function index(): Response
    {
        return $this->render('json/index.html.twig', [
            'controller_name' => 'JsonController',
        ]);
    }

    #[Route('/UserJSON/{id}', name: 'readUsersJson')]
    public function listeUsers(
        UtilisateurRepository $repo,
        SerializerInterface $serializer,
        Request $req
    ) {

        $id = $req->get("id");
        $utilisateurs = $repo->findOneById($id);
        // $serializer = new Serializer([new ObjectNormalizer()]);
        $json = $serializer->serialize($utilisateurs, 'json');
        return new Response($json);
    }



    #[Route('/addUtilisateurJSON', name: 'signupJson')]
    public function addUtilisateur(
        ManagerRegistry $doctrine,
        UserPasswordEncoderInterface $userPasswordEncoder,
        Request $req,
        SessionInterface $session,
        NormalizerInterface $normalizer,
        RoleRepository $rolerepo

    ) {
        $user = new Utilisateur();

        $user->setSalt('abcdef');
        $user->setCode('0000');

        $em = $doctrine->getManager();
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        $user->setTel($req->get('tel'));
        $user->setAdresse($req->get('adresse'));
        $plainPassword = $req->get('mdp');
            $encodedPassword = $userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setMdp( $encodedPassword);

        $user->setDatenaissance(new \DateTime($req->get('datenaissance')));
        $role = $req->get('role');
        $user->setIdrole($rolerepo->findOneByDescription($role));
        $em->persist($user);
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer]);
        $formatted  = $serialize->normalize("Utilisateur ajoute avec succes");
        return new JsonResponse($formatted);
    }


    #[Route('/deleteUserJSON/{id}', name: 'deleteUserJson')]
    public function deleteUser($id, ManagerRegistry $doctrine, NormalizerInterface $normalizer)
    {
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer]);
        $formatted  = $serialize->normalize("Utilisateur supprime avec succes");
        //$jsonContent = $normalizer->normalize("Utilisateur supprimé avec succès");
        return new JsonResponse($formatted);
    }

    #[Route('/updateUserJSON/{id}', name: 'updateUserJson')]
    public function updateUser(
        Request $req,
        $id,
        ManagerRegistry $doctrine,
        RoleRepository $rolerepo,
        NormalizerInterface $normalizer
    ) {
        $user = $doctrine->getRepository(Utilisateur::class)->find($id);

        $em = $doctrine->getManager();
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        $user->setTel($req->get('tel'));
        $user->setAdresse($req->get('adresse'));
        $user->setMdp($req->get('mdp'));
        $user->setDatenaissance(new \DateTime($req->get('datenaissance')));
        $role = $req->get('role');
        $user->setIdrole($rolerepo->findOneByDescription($role));
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer]);
        $formatted  = $serialize->normalize("Utilisateur modifie avec succes");
        //$jsonContent = $normalizer->normalize($utilisateur, 'json');
        //return new Response(json_encode($jsonContent));
        return new JsonResponse($formatted);
    }


    #[Route('/connexionJSON', name: 'loginJson')]
    public function login1(
        UtilisateurRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        Request $req,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        NormalizerInterface $normalizer,
        SerializerInterface $serializer
    ) {


        $email = $req->get('email');
        $mdp = $req->get('mdp');

        // Récupérer l'utilisateur correspondant à l'e-mail entré
        /* $email = $form->get('email')->getData();
            $plainPassword = $form->get('mdp')->getData();*/
        $user = $userRepository->findOneByEmail($email);

        // Vérifier si le mot de passe entré correspond à celui stocké dans la base de données
        if ($user != null && $userPasswordEncoder->isPasswordValid($user, $mdp)) {

            $json = $serializer->serialize($user, 'json');

            return new Response($json);
        } else {

            $json = $serializer->serialize(null, 'json');

            return new Response($json);
        }
    }



    #[Route('/logoutJSON', name: 'logoutJson')]
    public function logout(SessionInterface $session, NormalizerInterface $normalizer)
    {
        //$jsonContent = $normalizer->normalize($session->get('user'), 'json');
      //  $session->remove('user');
        $serialize = new Serializer([new ObjectNormalizer]);
        $formatted  = $serialize->normalize("vous etes deconnecte");
        return new JsonResponse($formatted);
    }

    /*
    #[Route('/sendmailJSON', name: 'sendmailJson')]
    public function sendMail(
        FlashyNotifier $flashy,
        Request $request,
        UtilisateurRepository $usersRepository,
        NormalizerInterface $normalizer,
    ) {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        $code = strval(rand(1000, 9999));
        $this->session->set('code', $code);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $this->session->set('email', $email);
            $user = $usersRepository->findOneByEmail($email);
            $user->setCode($code);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            if (!$user) {
                $error = 'Adresse e-mail non valide';
                dump($error);
                $flashy->error('Mail invalide!');
            } else {
                $subject = 'Code de vérification';
                $body = 'Bonjour ' . $user->getNom() . ' ' . $user->getPrenom() . ', '
                    . 'Pour votre demande de réinitialisation de mot de passe, veuillez insérer le code suivant : ' . ' '
                    .  $code;
                $this->mailService->sendEmail($user->getEmail(), $subject, $body);
                $jsonContent = $normalizer->normalize($user->getCode(), 'json');
                return new Response(json_encode($jsonContent));
                $this->addFlash('success', 'Un e-mail contenant un code de vérification a été envoyé à votre adresse e-mail.');
                dump('mail envoyé !', $code);
                $flashy->primaryDark('Mail envoyé!');
            }
        }
        return $this->render('utilisateur/forgotpwd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    */
}
