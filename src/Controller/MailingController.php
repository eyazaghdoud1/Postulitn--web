<?php

namespace App\Controller;

use App\Form\CheckCodeType;
use App\Form\ResetPasswordRequestFormSmsType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPwdType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailService;
use App\Service\TwilioSmsService;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twilio\Rest\Client;

class MailingController extends AbstractController
{
    private $mailService;
    private $session;
    public function __construct(MailService $mailService, SessionInterface $session)
    {
        $this->mailService = $mailService;
        $this->session = $session;
    }

    #[Route('/sendsms', name: 'sendsms')]
    public function sendsms(FlashyNotifier $flashy, Request $request, UtilisateurRepository $usersRepository)
    {
        $form = $this->createForm(ResetPasswordRequestFormSmsType::class);
        $form->handleRequest($request);
        $code = strval(rand(1000, 9999));
        $this->session->set('code', $code);
        if ($form->isSubmitted() && $form->isValid()) {
            $tel = $form->get('tel')->getData();
            $this->session->set('tel', $tel);
            $user = $usersRepository->findOneByTel($tel);
            $user->setCode($code);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            if (!$user) {
                $error = 'Adresse e-mail non valide';
                dump($error);
                $flashy->error('Mail invalide!');
            } else {

                $account_sid = $this->getParameter('twilio.sid');
                $auth_token =  $this->getParameter('twilio.auth_token');
                $twilio_phone_number =  $this->getParameter('twilio.from_number');
                $receiver_phone_number = '+216' . $user->getTel();


                $client = new Client($account_sid, $auth_token);

                $client->messages->create(
                    $receiver_phone_number,
                    array(
                        "from" => $twilio_phone_number,
                        "body" => ' ' . $code . ' '

                    )
                );

                /* $toNumber = $user->getTel();
                $body = $code;
                $this->smsService->sendSms($toNumber, $body);*/
                $this->addFlash('success', 'Un sms contenant un code de vérification vous a été envoyé.');
                dump('sms envoyé !', $code);
                $flashy->primaryDark('Sms envoyé!');
                return $this->redirectToRoute('checkcode');
            }
        }
        return $this->render('utilisateur/forgotpwdsms.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/sendmail', name: 'sendmail')]
    public function sendMail(FlashyNotifier $flashy, Request $request, UtilisateurRepository $usersRepository)
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
                $flashy->error('Mail invalide!');
            } else {


                $subject = 'Code de vérification';
                $body = 'Bonjour ' . $user->getNom() . ' ' . $user->getPrenom() . ', '
                    . 'Pour votre demande de réinitialisation de mot de passe, veuillez insérer le code suivant : ' . ' '
                    .  $code;
                $this->mailService->sendEmail($user->getEmail(), $subject, $body);
                $this->addFlash('success', 'Un e-mail contenant un code de vérification a été envoyé à votre adresse e-mail.');
                dump('mail envoyé !', $code);
                $flashy->primaryDark('Mail envoyé!');
                return $this->redirectToRoute('checkcode');
            }
        }
        return $this->render('utilisateur/forgotpwd.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/checkcode', name: 'checkcode')]
    public function checkcode(FlashyNotifier $flashy, Request $req): Response
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
                $flashy->primaryDark('code valide!');
                return $this->redirectToRoute('modifmdp');
            } else {
                $error = 'code non valide';
                dump($error);
                $flashy->error('code non valide!');
            }
        }

        return $this->render('utilisateur/codeLogin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/resetpwd', name: 'modifmdp')]
    public function resetpwd(FlashyNotifier $flashy, Request $req, UtilisateurRepository $usersRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $session = $req->getSession();
        $code = $session->get('code');
        $email = $session->get('email');
        $tel = $session->get('tel');
        $user = $usersRepository->findOneByCode($code);
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
            $flashy->primaryDark('Mot de passe changé!');
        }
        return $this->render('utilisateur/changepwd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
