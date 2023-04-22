<?php

namespace App\Controller;

use App\Entity\Quizquestions;
use App\Form\QuizQuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
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


/*
     #[Route('/admin/newquiz/{id}/questions', name: 'addQuizQuestion')]
    public function addQuizQuestions(ManagerRegistry $doctrine,Request $request, QuizRepository $quizRepository,$id): Response {
        $quiz = $quizRepository->find($id);
        $quizquestions = [];
        for ($i = 1; $i <= 2; $i++) {
            $quizquestions[] = new Quizquestions();
            $quizquestions[$i - 1]->setIdquiz($quiz);
        }
        $forms = [];
        foreach ($quizquestions as $quizquestion) {
            $forms[] = $this->createForm(QuizQuestionType::class, $quizquestion);
        }
    
        $submitted = false;
        $currentFormIndex = 0;
        foreach ($forms as $key => $form) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->persist($quizquestions[$key]);
                $em->flush(); 
                $currentFormIndex ++;
                
                if ($currentFormIndex==count($forms)) {
                     $submitted = true;
            } else {
                break;
            }
            }
        }
        $formViews = [];
        foreach ($forms as $key => $form) {
            $formViews[$key] = $form->createView();
        }
        if ($submitted) {
            return $this->redirectToRoute('adminReadQuiz');
        }
       else {
        return $this->render('quizquestions/addquizquestion.html.twig', [
            'forms' => $formViews ,
            'quiz' => $quiz,
            'qstnumber' => $currentFormIndex+1
        ]);
    
    }
}
    */
   
    #[Route('/admin/newquiz/{id}/questions', name: 'addQuizQuestion')]
    public function addQuizQuestions(
        ManagerRegistry $doctrine,
        Request $request,
        QuizRepository $quizRepository,
        $id
    ): Response {
        $quiz = $quizRepository->find($id);
        $quizquestions = [];
        for ($i = 1; $i <= 2; $i++) {
            $quizquestion = new Quizquestions();
            $quizquestion->setIdquiz($quiz);
            $quizquestions[] = $quizquestion;
        }
        $currentQuestion = 0;
        $form = $this->createForm(QuizQuestionType::class, $quizquestions[$currentQuestion]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           /* $em = $doctrine->getManager();
            $em->persist($quizquestions[$currentQuestion]);
            $em->flush();*/
            $currentQuestion++;
            if ($currentQuestion == 2) {
                return $this->redirectToRoute('adminReadQuiz');
            } else {
                $form = $this->createForm(QuizQuestionType::class, $quizquestions[$currentQuestion]);
                
            }
        }
        return $this->render('quizquestions/addquizquestion.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz,
            'qstnumber' => $currentQuestion + 1,
        ]);
        
    }
    

    
    
   
    
}
