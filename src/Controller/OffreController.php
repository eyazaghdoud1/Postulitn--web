<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;
use App\Repository\TypeoffreRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Entity\Offre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Types\This;
use App\Form\OffreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Typeoffre;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\SlidingPaginationInterface;
use App\Form\SearchType;
use Twilio\Rest\Client;

use App\Form\SearchData;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OffreController extends AbstractController
{
    #[Route('/offres/recruteur', name: 'app_offre')]
    public function index(OffreRepository $offreRepository, Request $request, PaginatorInterface $paginator,
     TypeoffreRepository $typerepo, SessionInterface $session): Response
{
    if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Recruteur'){
    $searchData = new SearchData();
    $form = $this->createForm(SearchType::class, $searchData);
    $form->handleRequest($request);

    $criteria = [];

    if ($form->isSubmitted() && $form->isValid()) {
        //$searchData->page = $request->query->getInt('page', 1);

        // Vérifier quel critère est rempli et ajouter au tableau de critères en conséquence
        if (!empty($searchData->poste)) {
            $criteria['like_poste'] = $searchData->poste;
        }
        if (!empty($searchData->lieu)) {
            $criteria['like_lieu'] = $searchData->lieu;
        }
        if (!empty($searchData->dateexpiration)) {
            $dateString = $searchData->dateexpiration->format('Y-m-d');

            $criteria['like_dateexpiration'] = $dateString;
        }
    }

    $offres = $paginator->paginate(
        $offreRepository->findByCriteria($criteria),
        $request->query->getInt('page', 1),
        3
    );

    $typeoffres = $typerepo->findAll();

    return $this->render('offre/index.html.twig', [
        'form' => $form->createView(),
        'offres' => $offres,
        //'dateExpiration' => $dateExpiration
    ]);} else {
        return $this->render('notfound.html.twig');
    }
}
#[Route('/offres/candidat', name: 'app_offre1', methods: ['GET'])]
    public function index1(OffreRepository $offreRepository, Request $request, PaginatorInterface $paginator, 
    TypeoffreRepository $typerepo, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Candidat'){
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
    
        $criteria = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            //$searchData->page = $request->query->getInt('page', 1);
    
            // Vérifier quel critère est rempli et ajouter au tableau de critères en conséquence
            if (!empty($searchData->poste)) {
                $criteria['like_poste'] = $searchData->poste;
            }
            if (!empty($searchData->lieu)) {
                $criteria['like_lieu'] = $searchData->lieu;
            }
            if (!empty($searchData->dateexpiration)) {
                $dateString = $searchData->dateexpiration->format('Y-m-d');
    
                $criteria['like_dateexpiration'] = $dateString;
            }
        }
    
        $offres = $paginator->paginate(
            $offreRepository->findByCriteria($criteria),
            $request->query->getInt('page', 1),
            3
        );
    
        $typeoffres = $typerepo->findAll();
    
        return $this->render('offre/index1.html.twig', [
            'form' => $form->createView(),
            'offres' => $offres,
            //'dateExpiration' => $dateExpiration
        ]);} else {
            return $this->render('notfound.html.twig');
        }
    }

 
  /**
 * @Route("/delete/{id}", name="delete")
 * @param OffreRepository $repo
 * @param ManagerRegistry $doctrine
 * @param $id
 * @return Response

 */
public function delete(OffreRepository $repo, ManagerRegistry $doctrine, $id,FlashyNotifier $flashy,
SessionInterface $session): Response
{

  if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Recruteur'){
    $objet=$repo->find($id);
    $em=$doctrine->getManager();
    $em->remove($objet);
    $em->flush();
    $flashy->primaryDark('offre supprime avec succes', 'http://your-awesome-link.com');
    return $this->redirectToRoute('app_offre');
} else {
    return $this->render('notfound.html.twig');
}
}  

#[Route('/createOffre', name: 'createOffre')]
public function create( UtilisateurRepository $userrepo, ManagerRegistry $doctrine, Request $request,FlashyNotifier $flashy, SessionInterface $session): Response
{
    if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Recruteur'){
    $offre = new Offre();
    $offre->setIdrecruteur($userrepo->find($session->get('user')->getId()));
    //$offre->setIdrecruteur(1); // Ici, on fixe la valeur de l'id recruteur à 1

    //$form = $this->createForm(OffreType::class, $offre);
    $form = $this->createForm(OffreType::class, $offre);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($offre);
        $em->flush();
        $flashy->success('offre ajoute avec succes', 'http://your-awesome-link.com');

      /* $account_sid = $this->getParameter('twilio.sid');
        $auth_token = $this->getParameter('twilio.auth_token');
        $twilio_phone_number = $this->getParameter('twilio.from_number');
        $receiver_phone = '+21627324205';
        
        $client = new Client($account_sid, $auth_token);

        $client->messages->create(
            $receiver_phone,
            array(
                "from"=> $twilio_phone_number,
                "body"=>  "Your offer has been successfully saved thank you for using Posutli"
            )
            );*/

        
        return $this->redirectToRoute('app_offre');
    }

    return $this->renderForm('offre/ajouteroffre.html.twig', [
        'form' => $form, // Passer l'objet de formulaire directement
    ]);} else {
        return $this->render('notfound.html.twig');
    }
}
#[Route('/update/{id}', name: 'update')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, OffreRepository $repo,
    SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Recruteur'){
        $offre = $repo->find($id);

        $form = $this->createForm(OffreType::class, $offre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en = $doctrine->getManager();
            $en->flush();

            return $this->redirectToRoute('app_offre');
        }

        return $this->render('offre/modifier.html.twig', ['form' => $form->createView()]);
    } else {
        return $this->render('notfound.html.twig');
    } 
    }
    #[Route('/detailsoffre/recruteur/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre , OffreRepository $repo, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Recruteur'){

        $offrewithtype = $repo->findOneWithType($offre->getIdoffre());

        $similarOffers = $repo->findSimilarOffers($offre);


        
        return $this->render('offre/showoffre.html.twig', [
            'similarOffers' => $similarOffers,
            'offre' => $offrewithtype,
        ]);} else {
            return $this->render('notfound.html.twig');
        } 
    }
    
    #[Route('/detailsoffre/candidat/{id}', name: 'app_offre_show1', methods: ['GET'])]
    public function show1(Offre $offre , OffreRepository $repo, SessionInterface $session): Response
    {
        
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Candidat'){

        $offrewithtype = $repo->findOneWithType($offre->getIdoffre());

        $similarOffers = $repo->findSimilarOffers($offre);


        
        return $this->render('offre/showoffre1.html.twig', [
            'similarOffers' => $similarOffers,
            'offre' => $offrewithtype,
        ]);} else {
            return $this->render('notfound.html.twig');
        } 
    }

    
    
    
    #[Route('offre/stats', name: 'stats')]
public function statistiques(TypeoffreRepository $typerepo, OffreRepository $offrerepo, SessionInterface $session)
{
    if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
    // On va chercher tous les types d'offres
    $types = $typerepo->findAll();

    $typeNom = [];
    $typeCount = [];

    foreach ($types as $type) {
        $typeNom[] = $type->getDescription();

        $offres = $offrerepo->findBy(['idtype' => $type->getIdtype()]);
        $typeCount[] = count($offres);
    }
      // On va chercher le nombre d'annonces publiées par date
      $annonces = $offrerepo->countByDate();
      $dates = [];
      $annoncesCount = [];

      // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
      foreach($annonces as $offre){
          $dates[] = $offre['dateExpiration'];
          $annoncesCount[] = $offre['count'];
      }
    return $this->render('offre/stats.html.twig', [
        'typeNom' => json_encode($typeNom),
        'typeCount' => json_encode($typeCount),
        'dates' => json_encode($dates),
        'annoncesCount' => json_encode($annoncesCount),
    ]);} else {
        return $this->render('notfound.html.twig');
    } 
}
}
//mazel cntrl saisie
    

