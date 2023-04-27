<?php

namespace App\Controller;

use App\Form\CheckCodeType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPwdType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MailingController extends AbstractController
{
    private $mailService;
    private $session;

    public function __construct(MailService $mailService, SessionInterface $session)
    {
        $this->mailService = $mailService;
        $this->session = $session;
    }

    #[Route('/sendmail', name: 'sendmail')]
    public function sendMail(Request $request, UtilisateurRepository $usersRepository)
    {
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
            } else {


                $subject = 'Code de vérification';
                $body = 'Bonjour ' . $user->getNom() . ' ' . $user->getPrenom() . ', '
                    . 'Pour votre demande de réinitialisation de mot de passe, veuillez insérer le code suivant : ' . ' '
                    .  $code;
                $this->mailService->sendEmail($user->getEmail(), $subject, $body);
                $this->addFlash('success', 'Un e-mail contenant un code de vérification a été envoyé à votre adresse e-mail.');
                dump('mail envoyé !', $code);
                return $this->redirectToRoute('checkcode');
            }
        }
        return $this->render('utilisateur/forgotpwd.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/checkcode', name: 'checkcode')]
    public function checkcode(Request $req): Response
    {
        $session = $req->getSession();
        $codeEnvoye = $session->get('code');

        $form = $this->createForm(CheckCodeType::class);
        $form->handleRequest($req);
        // $user = $usersRepository->findOneByCode($code);

        if ($form->isSubmitted() && $form->isValid()) {
            $codesaisi = $form->get('code')->getData();
            /* var_dump($codeinsere);
                var_dump($code);
                die(); */
            if ($codesaisi == $codeEnvoye) {
                dump('succès');
                return $this->redirectToRoute('modifmdp');
            } else {
                $error = 'code non valide';
                dump($error);
            }
        }

        return $this->render('utilisateur/codeLogin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/resetpwd', name: 'modifmdp')]
    public function resetpwd(Request $req, UtilisateurRepository $usersRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $session = $req->getSession();
        $code = $session->get('code');
        $email = $session->get('email');
        $user = $usersRepository->findOneByEmail($email);
        $form = $this->createForm(ResetPwdType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('mdp')->getData();
            $encodedPassword = $userPasswordEncoder->encodePassword($user, $plainPassword);
            $user->setMdp($encodedPassword);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('utilisateur/changepwd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
