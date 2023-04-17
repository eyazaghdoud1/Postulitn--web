<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quiz;
use App\Repository\QuizRepository;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'app_quiz')]
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }

    #[Route('/candidat/quizlist', name: 'readQuiz')]
    public function readQuiz(QuizRepository $Repo ): Response
    {
        $list = $Repo->findAll();
       // $count = $Rep->numberOfCandidaturePerOffre(53);
        
        return $this->render('quiz/candidatquizlist.html.twig', [
            'list' => $list,
            // 'count' => $count
        ]);
    }
}
