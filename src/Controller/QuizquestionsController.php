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
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\VarDumper\VarDumper;

class QuizquestionsController extends AbstractController
{
    #[Route('/quizquestions', name: 'app_quizquestions')]
    public function index(): Response
    {
        return $this->render('quizquestions/index.html.twig', [
            'controller_name' => 'QuizquestionsController',
        ]);
    }
   
   
    #[Route('/admin/newquiz/{id}/questions', name: 'addQuizQuestion')]
    public function addQuizQuestions(
        ManagerRegistry $doctrine,
        Request $request,
        QuizRepository $quizRepository,
        $id, 
        QuizquestionsRepository $qqrepo,
        FlashyNotifier $flashy
    ): Response {
        $quiz = $quizRepository->find($id);
        $quizquestions = [];
        for ($i = 0; $i <= 1; $i++) {
            $quizquestion = new Quizquestions();
            $quizquestion->setIdquiz($quiz);
            $quizquestions[$i] = $quizquestion;
        }
        $addedquestions=$qqrepo->findByQuiz($id); 
        $currentQuestion = count($addedquestions);
        $form = $this->createForm(QuizQuestionType::class, $quizquestions[$currentQuestion]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($quizquestions[count($addedquestions)]);
            $em->flush();
            
            
            if (count($qqrepo->findByQuiz($id)) == 2) {
                // notif
                $flashy->success('Le quiz ' . $quiz->getNom() . ' a été enregistré avec succès.');
                return $this->redirectToRoute('adminReadQuiz');
                
            } else {
                $addedquestions=$qqrepo->findByQuiz($id);
                $form = $this->createForm(QuizQuestionType::class, $quizquestions[count($addedquestions)]);
                
                
            }
        }
        return $this->render('quizquestions/addquizquestion.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz,
            'qstnumber' => count($addedquestions)+1,
        ]);
        
    }
    

    /** 
     * 
     * delete quiz question method
     */
    #[Route('/admin/quiz/deletequestion/{idquiz}', name: 'deleteQuizQuestion')]
    public function delete(QuizquestionsRepository $repo,ManagerRegistry $doctrine, $idquiz,
     FlashyNotifier $flashy) : Response
    {
        $questions = $repo->findByQuiz($idquiz);
        for($i=0; $i<count($questions); $i++) {
            
            $em = $doctrine->getManager();
            $em->remove($questions[$i]);
            $em->flush();
          
        }
          // notif
          $flashy->warning('Le quiz a été supprimé avec succès.');
        
        return $this->redirectToRoute('adminReadQuiz');
       
    }
   
    
}
