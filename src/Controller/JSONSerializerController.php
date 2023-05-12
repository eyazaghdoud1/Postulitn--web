<?php

namespace App\Controller;

use App\Entity\Candidatures;
use App\Entity\Entretiens;
use App\Entity\Quizscores;
use App\Repository\CandidaturesRepository;
use App\Repository\EntretiensRepository;
use App\Repository\GuidesentretiensRepository;
use App\Repository\OffreRepository;
use App\Repository\QuizquestionsRepository;
use App\Repository\QuizRepository;
use App\Repository\QuizscoresRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class JSONSerializerController extends AbstractController
{
    /************* Candidatures ***************/
    #[Route('/allCandidatures/{role}/{id}', name: 'listCandidaturesJSON')]
    public function getCandidaturesJSON(
        CandidaturesRepository $repo,
        $role,
        $id,
        SerializerInterface $serializer
    ) {
        if ($role == "Candidat") {
            $candidatures = $repo->findByCandidat($id);
        } else {
            $candidatures = $repo->findByOffre($id);
        }

        $json = $serializer->serialize($candidatures, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    /*#[Route('/all', name: 'listCandidatures')]
    public function getCandidatures(CandidaturesRepository $repo,
   
     SerializerInterface $serializer)
    {
       
     $candidatures = $repo->findAll();
        
       
        $json = $serializer->serialize($candidatures, 'json', ['groups'=>'candidatures']);
        return new Response($json);
        
    }
*/
    #[Route('/candidature/{id}', name: 'candidature')]
    public function getCandidatureByIdJSON(Request $req, CandidaturesRepository $repo, SerializerInterface $serializer)
    {
        $candidatures = $repo->find($req->get('id'));
       
        $json = $serializer->serialize($candidatures, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    #[Route('/addCandidatureJSON/{idoffre}/{idcandidat}', name: 'addCandidatureJSON')]
    public function addCandidatureJSON(OffreRepository $offrerepo, UtilisateurRepository $userrepo,
     Request $req, 
    ManagerRegistry $doctrine, SerializerInterface $serializer, $idcandidat, $idoffre)
    {
        $candidature = new Candidatures();
        $candidature->setIdcandidat($userrepo->find($idcandidat));
        $candidature->setIdoffre($offrerepo->find($idoffre));
        copy($req->get('cv'),"C:/xampp/htdocs/postulitn/cv/". $req->get('cvnom') );
        $candidature->setCv($req->get('cvnom'));
        
       
        copy($req->get('lettre'),"C:/xampp/htdocs/postulitn/lettres/". $req->get('lettrenom') );
        $candidature->setLettre($req->get('lettrenom'));
        $candidature->setDate(new \DateTime('now'));
        $candidature->setEtat("Enregistrée");

        $em = $doctrine->getManager();
        $em->persist($candidature);
        $em->flush();

        $json = $serializer->serialize($candidature, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    #[Route('/updateCandidatureJSON/{id}', name: 'updateCandidatureJSON')]
    public function updateCandidatureJSON(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer, $id)
    {
        $em = $doctrine->getManager();
        $candidature = $em->getRepository(Candidatures::class)->find($id);
        copy($req->get('cv'),"C:/xampp/htdocs/postulitn/cv/". $req->get('cvnom') );
        $candidature->setCv($req->get('cvnom'));
        //copy("C:/xampp/htdocs/postulitn/cv/CV_ZINELABIDINE_Eya.pdf","C:/xampp/htdocs/postulitn/lettres/l.pdf");
       
        copy($req->get('lettre'),"C:/xampp/htdocs/postulitn/lettres/". $req->get('lettrenom') );
        $candidature->setLettre($req->get('lettrenom'));
        //$em->persist($candidature);
        $em->flush();

        $json = $serializer->serialize($candidature, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    #[Route('/deleteCandidatureJSON/{id}', name: 'deleteCandidatureJSON')]
    public function deleteCandidatureJSON(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer)
    {
        $em = $doctrine->getManager();
        $candidature = $em->getRepository(Candidatures::class)->find($req->get('id'));

        $em->remove($candidature);
        $em->flush();

        $json = $serializer->serialize($candidature, 'json', ['groups' => 'candidatures']);
        return new Response('deleted' . $json);
    }

    /* methods for changing etat candidature */
    #[Route('/validerCandidatureJSON/{id}', name: 'validerCandidatureJSON')]
    public function validerJSON(
        CandidaturesRepository $repo,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        Request $req
    ) {

        $c = $repo->find($req->get('id'));
        $c->setEtat('Validée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();

        $json = $serializer->serialize($c, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    #[Route('/accepterCandidatureJSON/{id}', name: 'accepterCandidatureJSON')]
    public function accepterJSON(
        CandidaturesRepository $repo,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        Request $req
    ) {

        $c = $repo->find($req->get('id'));
        $c->setEtat('Acceptée');
        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();

        $json = $serializer->serialize($c, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    #[Route('/refuserCandidatureJSON/{id}', name: 'refuserCandidatureJSON')]
    public function refuserJSON(
        CandidaturesRepository $repo,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        Request $req
    ) {
        $c = $repo->find($req->get('id'));
        $c->setEtat('Refusée');

        $em = $doctrine->getManager();
        $em->persist($c);
        $em->flush();

        $json = $serializer->serialize($c, 'json', ['groups' => 'candidatures']);
        return new Response($json);
    }

    /******************** entretiens ***************/
    #[Route('/entretiensJSON/{role}/{id}', name: 'listEntretiensJSON')]
    public function readEntretiensJSON(
        Request $req,
        EntretiensRepository $Rep,
        SerializerInterface $serializer
    ) {
        if ($req->get('role') == 'Recruteur') {
            $list = $Rep->findByRecruteur($req->get('id'));
        } else {
            $list = $Rep->findByCandidat($req->get('id'));
        }
        $json = $serializer->serialize($list, 'json', ['groups' => 'entretiens']);
        return new Response($json);
    }

    #[Route('/entretiensCanidatureJSON/{id}', name: 'entretiensCandidatureJSON')]
    public function entretiensCandidatureJSON(
        Request $req,
        EntretiensRepository $Rep,
        SerializerInterface $serializer
    ) {

        $list = $Rep->filterByCandidature($req->get('id'));

        $json = $serializer->serialize($list, 'json', ['groups' => 'entretiens']);
        return new Response($json);
    }

    #[Route('/addEntretienJSON/{idcandidature}', name: 'addEntretiensJSON')]
    public function addEntretienJSON(
        Request $req,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        CandidaturesRepository $candrepo,
        GuidesentretiensRepository $grepo
    ) {

        $e = new Entretiens();
        $e->setIdcandidature($candrepo->find($req->get('idcandidature')));
        $e->setType($req->get('type'));
        $e->setDate(new \DateTime($req->get('date')));
        $e->setLieu($req->get('lieu'));
        $e->setHeure($req->get('horaire'));
        $e->setIdguide($grepo->find(13));

        $em = $doctrine->getManager();
        $em->persist($e);
        $em->flush();

        $json = $serializer->serialize($e, 'json', ['groups' => 'entretiens']);
        return new Response($json);
    }

    #[Route('/updateEntretienJSON/{id}', name: 'updateEntretiensJSON')]
    public function updateEntretienJSON(
        Request $req,
        ManagerRegistry $doctrine,
        EntretiensRepository $erepo,
        SerializerInterface $serializer
    ) {

        $e = $erepo->find($req->get('id'));
        $e->setDate(new \DateTime($req->get('date')));
        $e->setLieu($req->get('lieu'));
        $e->setHeure($req->get('horaire'));

        $em = $doctrine->getManager();
        $em->persist($e);
        $em->flush();

        $json = $serializer->serialize($e, 'json', ['groups' => 'entretiens']);
        return new Response($json);
    }

    #[Route('/deleteEntretienJSON/{id}', name: 'deleteEntretiensJSON')]
    public function deleteEntretienJSON(
        Request $req,
        ManagerRegistry $doctrine,
        EntretiensRepository $erepo,
        SerializerInterface $serializer
    ) {

        $e = $erepo->find($req->get('id'));

        $em = $doctrine->getManager();
        $em->remove($e);
        $em->flush();

        $json = $serializer->serialize($e, 'json', ['groups' => 'entretiens']);
        return new Response($json);
    }

    /***** Quiz */
    #[Route('/listQuizJSON', name: 'listQuizJSON')]
    public function listQuizJSON(
        QuizRepository $Rep,
        SerializerInterface $serializer
    ) {

        $list = $Rep->findAll();
        $json = $serializer->serialize($list, 'json', ['groups' => 'quiz']);
        return new Response($json);
    }

    #[Route('/quizJSON/{id}', name: 'quizJSON')]
    public function quizJSON(
        Request $req,
        QuizquestionsRepository $Rep,
        SerializerInterface $serializer
    ) {

        $list = $Rep->findByQuiz($req->get('id'));

        $json = $serializer->serialize($list, 'json', ['groups' => 'quiz']);
        return new Response($json);
    }

    #[Route('/passerquizJSON/{idquiz}/{idcandidat}', name: 'quizQuestionsJSON')]
    public function readQuizQuestionsJSON(
        ManagerRegistry $doctrine,
        QuizscoresRepository $qsRepo,
        UtilisateurRepository $userRepo,
        QuizRepository $QuizRepo,
      
        QuizquestionsRepository $Repo,
        Request $req,
        SerializerInterface $serializer

    ) {
        $quiz = $QuizRepo->find($req->get('idquiz'));
        $list = $Repo->findByQuiz($quiz);

        //$oldqs = $qsRepo->findByCandidatAndQuiz(69, $id);


        $correct_answers = [];
        for ($i = 0; $i <= 4; $i++) {
            $correct_answers[$i] = $list[$i]->getReponse();
        }
        $score = 0;


        $candidat_answers = [];
        $candidat_answers[0] = $req->get('rep1');
        $candidat_answers[1] = $req->get('rep2');
        $candidat_answers[2] = $req->get('rep3');
        $candidat_answers[3] = $req->get('rep4');
        $candidat_answers[4] = $req->get('rep5');

        // counting the score
        $score = $this->score($correct_answers, $candidat_answers);
        // saving the score
        $qscore =$qsRepo->findByCandidatAndQuiz($userRepo->find($req->get('idcandidat')), $quiz);
        if ($qscore == null) {

        $qscore = new Quizscores();
        $qscore->setIdquiz($quiz);
        $qscore->setDate(new \DateTime('now'));
        $qscore->setScore($score);
        $qscore->setIdcandidat($userRepo->find($req->get('idcandidat')));
        $em = $doctrine->getManager();
        $em->persist($qscore);
        $em->flush();
        } else {
            
            $qscore->setDate(new \DateTime('now'));
            $qscore->setScore($score);
            $em = $doctrine->getManager();
            $em->persist($qscore);
            $em->flush();

        }
      

        $json = $serializer->serialize($score, 'json', ['groups' => 'quiz']);
        return new Response($json);
    }

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
}
