<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;
use App\Repository\TypeoffreRepository;

use App\Entity\Offre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Types\This;
use App\Form\OffreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Typeoffre;







class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(OffreRepository $Rep): Response
    {
        //return $this->render('offre/index.html.twig', [
            //'controller_name' => 'OffreController',
            $list = $Rep->findAll();
        return $this->render('offre/index.html.twig', ['list' => $list
        ]);
    }
 
  /**
 * @Route("/delete/{id}", name="delete")
 * @param OffreRepository $repo
 * @param ManagerRegistry $doctrine
 * @param $id
 * @return Response

 */
public function delete(OffreRepository $repo, ManagerRegistry $doctrine, $id): Response
{

    $objet=$repo->find($id);
    $em=$doctrine->getManager();
    $em->remove($objet);
    $em->flush();
    return $this->redirectToRoute('app_offre');

}  
#[Route('/createOffre', name: 'createOffre')]
public function create(ManagerRegistry $doctrine, Request $request): Response
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
    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/showoffre.html.twig', [
            'offre' => $offre,
        ]);
    }
    

    #[Route('/offrecandidat', name: 'app_offre_index1', methods: ['GET'])]
    public function index1(OffreRepository $repo): Response
    {
        return $this->render('offre/index1.html.twig', [
            'offres' => $repo->findAll(),
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
    

