<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Entity\Quizscores;
use App\Repository\UtilisateurRepository;
use App\Repository\QuizquestionsRepository;
use App\Repository\QuizscoresRepository;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'app_quiz')]
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }

    /**
     * 
     * read all for candidat method
     */
    #[Route('/candidat/quizlist', name: 'candidatReadQuiz')]
    public function readQuiz(QuizRepository $Repo): Response
    {
        $list = $Repo->findAll();

        return $this->render('quiz/candidatquizlist.html.twig', [
            'list' => $list,
            // 'count' => $count
        ]);
    }
    /**
     * 
     * read all for admin method
     */
    #[Route('/admin/quizlist', name: 'adminReadQuiz')]
    public function adminReadQuiz(QuizRepository $Repo): Response
    {
        $list = $Repo->findAll();

        return $this->render('quiz/adminquizlist.html.twig', [
            'list' => $list,
            // 'count' => $count
        ]);
    }


    #[Route('/admin/newquiz', name: 'addQuiz')]
    public function addQuiz(
        ManagerRegistry $doctrine,
        Request $request,
        QuizRepository $repo
    ): Response {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($quiz);
            $em->flush();
            return $this->redirectToRoute('addQuizQuestion', ['id' => $quiz->getId()]);
        } else
            return $this->renderForm('quiz/addquiz.html.twig', ['form' => $form]);
    }

    /**
     * 
     * pass quiz method
     */
    #[Route('/candidat/passerquiz/{id}', name: 'quizQuestions')]
    public function readQuizQuestions(ManagerRegistry $doctrine, QuizscoresRepository $qsRepo, UtilisateurRepository $userRepo, QuizRepository $QuizRepo, Request $request, QuizquestionsRepository $Repo, $id): Response
    {
        $quiz = $QuizRepo->find($id);
        $list = $Repo->findByQuiz($quiz);
        /*$oldqs = $qsRepo->findByCandidatAndQuiz($userRepo->find(69), $quiz);
        
        if ($oldqs != null and $oldqs->getDate()<new \DateTime('-1 month')) {
            return $this->redirectToRoute('resultatQuiz', [
                'id' => $id,
                'score' => $oldqs->getScore()
            ]);

        } else {*/
        // the quiz correct answers
        $correct_answers = [];
        for ($i = 0; $i <= 4; $i++) {
            $correct_answers[$i] = $list[$i]->getReponse();
        }
        $score = 0;
        if ($request->isMethod('POST')) {
            // the candidat's answers
            $answers = [];
            foreach ($request->request->all() as $key => $value) {
                $answers[$key] = $value;
            }

            $candidat_answers = [];
            $candidat_answers[0] = $answers['q0'][0];
            $candidat_answers[1] = $answers['q1'][0];
            $candidat_answers[2] = $answers['q2'][0];
            $candidat_answers[3] = $answers['q3'][0];
            $candidat_answers[4] = $answers['q4'][0];
            
            // counting the score
            $score = $this->score($correct_answers, $candidat_answers);
            // saving the score

            // if it's the candidat's first time passing the quiz
            if ($qsRepo->findByCandidatAndQuiz($userRepo->find(69), $quiz) == null) {
                $qscore = new Quizscores();
                $qscore->setIdquiz($quiz);
                $qscore->setDate(new \DateTime('now'));
                $qscore->setScore($score);
                $qscore->setIdcandidat($userRepo->find(69));
                $em = $doctrine->getManager();
                $em->persist($qscore);
                $em->flush();
            } else {
                // if the candidat has already passed the quiz before
                $qscore = $qsRepo->findByCandidatAndQuiz($userRepo->find(69), $quiz);
                $qscore->setDate(new \DateTime('now'));
                $qscore->setScore($score);
                $em = $doctrine->getManager();
                $em->persist($qscore);
                $em->flush();
            }
            //dump($score);
            //die;
            return $this->redirectToRoute('resultatQuiz', [
                'id' => $id,
                'score' => $score
            ]);
        }
        return $this->render('quizquestions/candidatquizquestions.html.twig', [
            'list' => $list,
            'quiz' => $quiz,
            
            // 'count' => $count
        ]);
    //}
    }

    /**
     * 
     * quiz result method
     */
    #[Route('/candidat/passerquiz/{id}/resultat/{score}', name: 'resultatQuiz')]
    public function resultquiz(QuizRepository $QuizRepo, $id, $score)
    {
        $quiz = $QuizRepo->find($id);
        return $this->render('quiz/quizresult.html.twig', [
            //'list' => $list,
            'quiz' => $quiz,
            'score' => $score
            // 'count' => $count
        ]);
    }

    /**
     * 
     * counting score method
     */

    public function score($correct_answers, $candidat_answers)
    {
        $score = 0;
        for ($i = 0; $i < count($correct_answers); $i++) {
            if ($candidat_answers[$i] == $correct_answers[$i]) {
                $score++;
            }
        }
        return $score;
    }

    /**
     * 
     * delete quiz method
     */
    #[Route('/admin/deletequiz/{id}', name: 'deleteQuiz')]
    public function delete(QuizquestionsController $qqcont ,QuizRepository $repo, QuizquestionsRepository $qqrepo,ManagerRegistry $doctrine, $id): Response
    {
        $objet = $repo->find($id);
        $questions = $qqrepo->findByQuiz($objet);
        $em = $doctrine->getManager();
        $em->remove($objet);
        $em->flush();
        for($i=0; $i<count($questions); $i++) {
            $qqcont->delete($qqrepo, $doctrine, $questions[$i]->getId());
        }
        return $this->redirectToRoute('adminReadQuiz');
    }
}
