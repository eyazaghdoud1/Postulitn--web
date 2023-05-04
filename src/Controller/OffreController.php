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






class OffreController extends AbstractController
{
    #[Route('/offres/recruteur', name: 'app_offre')]
    public function index(OffreRepository $offreRepository, Request $request, PaginatorInterface $paginator, TypeoffreRepository $typerepo): Response
{
    $searchData = new SearchData();
    $form = $this->createForm(SearchType::class, $searchData);
    $form->handleRequest($request);

    $criteria = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $searchData->page = $request->query->getInt('page', 1);

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
    ]);
}
#[Route('/offres/candidat', name: 'app_offre1', methods: ['GET'])]
    public function index1(OffreRepository $offreRepository, Request $request, PaginatorInterface $paginator, TypeoffreRepository $typerepo): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
    
        $criteria = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
    
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
        ]);
    }

 
  /**
 * @Route("/delete/{id}", name="delete")
 * @param OffreRepository $repo
 * @param ManagerRegistry $doctrine
 * @param $id
 * @return Response

 */
public function delete(OffreRepository $repo, ManagerRegistry $doctrine, $id,FlashyNotifier $flashy): Response
{

    $objet=$repo->find($id);
    $em=$doctrine->getManager();
    $em->remove($objet);
    $em->flush();
    $flashy->primaryDark('offre supprime avec succes', 'http://your-awesome-link.com');
    return $this->redirectToRoute('app_offre');
}  

#[Route('/createOffre', name: 'createOffre')]
public function create(ManagerRegistry $doctrine, Request $request,FlashyNotifier $flashy): Response
{
    $offre = new Offre();
    //$offre->setIdrecruteur(1); // Ici, on fixe la valeur de l'id recruteur à 1

    //$form = $this->createForm(OffreType::class, $offre);
    $form = $this->createForm(OffreType::class, $offre);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($offre);
        $em->flush();
        $flashy->success('offre ajoute avec succes', 'http://your-awesome-link.com');

        $sid = "ACdd813fb1473247d79540aff0ace9a161";
        $token = "d9bf0b1cd5c08bcca8da0d01e6df0849";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
                  ->create("+21696575810", // to
                           ["body" => "Your offer has been successfully saved thank you for using Posutli", "from" => "+16812216120"]
                  );
        return $this->redirectToRoute('app_offre');
    }

    return $this->renderForm('offre/ajouteroffre.html.twig', [
        'form' => $form, // Passer l'objet de formulaire directement
    ]);
}
#[Route('/update/{id}', name: 'update')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, OffreRepository $repo): Response
    {

        $offre = $repo->find($id);

        $form = $this->createForm(OffreType::class, $offre);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en = $doctrine->getManager();
            $en->flush();

            return $this->redirectToRoute('app_offre');
        }

        return $this->render('offre/modifier.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/detailsoffre/recruteur/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre , OffreRepository $repo): Response
    {

        $offrewithtype = $repo->findOneWithType($offre->getIdoffre());

        $similarOffers = $repo->findSimilarOffers($offre);


        
        return $this->render('offre/showoffre.html.twig', [
            'similarOffers' => $similarOffers,
            'offre' => $offrewithtype,
        ]);
    }
    
    #[Route('/detailsoffre/candidat/{id}', name: 'app_offre_show1', methods: ['GET'])]
    public function show1(Offre $offre , OffreRepository $repo): Response
    {

        $offrewithtype = $repo->findOneWithType($offre->getIdoffre());

        $similarOffers = $repo->findSimilarOffers($offre);


        
        return $this->render('offre/showoffre1.html.twig', [
            'similarOffers' => $similarOffers,
            'offre' => $offrewithtype,
        ]);
    }

    
    
    
    #[Route('offre/stats', name: 'stats')]
public function statistiques(TypeoffreRepository $typerepo, OffreRepository $offrerepo)
{
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
    ]);
}
}
//mazel cntrl saisie
    

