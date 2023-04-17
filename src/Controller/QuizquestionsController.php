<?php

namespace App\Controller;

use App\Entity\Quizquestions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizquestionsRepository;
use App\Repository\QuizRepository;

class QuizquestionsController extends AbstractController
{
    #[Route('/quizquestions', name: 'app_quizquestions')]
    public function index(): Response
    {
        return $this->render('quizquestions/index.html.twig', [
            'controller_name' => 'QuizquestionsController',
        ]);
    }

    #[Route('/candidat/passerquiz/{id}', name: 'quizQuestions')]
    public function readQuizQuestions(QuizRepository $QuizRepo, QuizquestionsRepository $Repo, $id ): Response
    {
        $quiz = $QuizRepo->find($id);
        $list = $Repo->findByQuiz($quiz);
       // $count = $Rep->numberOfCandidaturePerOffre(53);
        
        return $this->render('quizquestions/candidatquizquestions.html.twig', [
            'list' => $list,
            'quiz' => $quiz
            // 'count' => $count
        ]);
    }
}
