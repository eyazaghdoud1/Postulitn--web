<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntretiensRepository;

class MainController extends AbstractController
{
    #[Route('/postuli.tn', name: 'app_candidatures')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/basefront', name: 'basefront')]
    public function basefront(): Response
    {
        return $this->render('basefront.html.twig');
    }
    #[Route('/baseback', name: 'baseback')]
    public function baseback(): Response
    {
        return $this->render('baseback.html.twig');
    }
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('utilisateur/login.html.twig');
    }
}
