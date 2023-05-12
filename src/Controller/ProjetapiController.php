<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Repository\ProjetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\ProjetsType;
use App\Repository\OffreRepository;
use App\Repository\SecteursRepository; 
use App\Repository\TypeoffreRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\Commentairesepository;
use App\Entity\Utilisateur;
use App\Entity\Offre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormTypeInterface;

class ProjetapiController extends AbstractController
{
    #[Route('/AllProjetsjsoN', name: 'listProjetsjson')]
    public function getProjetsN(ProjetsRepository $repo, NormalizerInterface $normalizer)
    {
        $p = $repo->findAll();
        $ProjetsNormalizer = $normalizer->normalize($p, 'json', ['groups' => 'public']);
        $json = json_encode($ProjetsNormalizer);
        return new Response($json);
    }
    #[Route('/AllProjetsjsonN/{id}', name: 'listProjetsjsonByUser')]
    public function getProjetsbyUser(int $id,ProjetsRepository $repo, NormalizerInterface $normalizer)
    {
        $p = $repo->findby(['idresponsable' =>$id]);
        $ProjetsNormalizer = $normalizer->normalize($p, 'json', ['groups' => 'public']);
        $json = json_encode($ProjetsNormalizer);
        return new Response($json);
    }
    
    #[Route('/detailsProjetCandidatjson/{id}', name: 'detailsProjetCandidatjson')]
    public function readDetailsCandidat(ProjetsRepository $Rep, $id,NormalizerInterface $normalizer): Response
    {
        $p = $Rep->find($id);
        $ProjetsNormalizer = $normalizer->normalize($p, 'json',['groups' => 'public']);
        $json = json_encode($ProjetsNormalizer);
        return new Response($json);
    }


    #[Route('/addProjetsjson/{id}', name: 'addProjetsjson')]
    public function addProjetsjson(int $id,Request $req,ManagerRegistry $doctrine,OffreRepository $offrerepo,
    TypeoffreRepository $torepo, UtilisateurRepository $userrepo,SecteursRepository $secrepo,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager(); 
        $projets = new Projets();
    
        $projets->setIdResponsable($userrepo->find($id));
        $projets->setTheme($req->get('theme'));
        $projets->setDescription($req->get('description'));
        $projets-> setDuree($req->get('duree'));
        $projets->setDatedebut(new \DateTime($req->get('datedebut')));
        $projets->setDatefin(new \DateTime($req->get('datefin')));
        $projets->setNom($req->get('nom'));
        $projets->setNote(0);
        $secteur = $req->get('secteur');
        $projets->setIdsecteur($secrepo->findOneByDescription($secteur));
        $em->persist($projets);
        $em->flush();
        $this->addoffrejson($doctrine, $em->getRepository(Projets::class)->find($projets->getIdprojet()), $torepo, $userrepo, 67 , $normalizer);   
   $jsoncontent = $normalizer->normalize($projets,'json',['groups' => 'public']);
   return new Response("Projet added with success: ".json_encode($jsoncontent));
   }
    
    


    public function addoffrejson( ManagerRegistry $doctrine1,
    Projets $p, TypeoffreRepository $torepo, UtilisateurRepository $userrepo, $id,NormalizerInterface $normalizer) {
   // Create new Offre entity and set its attributes statically
   $offre = new Offre();
   $offre->setPoste($p->getNom());
   $offre->setDescription($p->getDescription());
   $offre->setDateexpiration($p->getDatefin());
   $offre->setLieu('none ');
   $offre->setEntreprise('none');
   $offre->setSpecialite('none');
   $offre->setIdtype($torepo->find(4)); 
   $offre->setIdrecruteur($userrepo->find($id));
    // Persist and flush the entity
   $em1=$doctrine1->getManager();
   $em1->persist($offre);
   $em1->flush();
   $jsoncontent = $normalizer->normalize($offre,'json' ,['groups' => 'public']);
   return new Response("projet ajouté avec succes " .json_encode($jsoncontent));
   }
   
   #[Route('/updateProjetsjson/{id}', name: 'update_Projetsjson')]
       public function updateProjets(int $id,ProjetsRepository $projetsRepository,Request $req,ManagerRegistry $doctrine,
       UtilisateurRepository $userrepo,TypeoffreRepository $typerepo,OffreRepository $offrerepo,SecteursRepository $secrepo,NormalizerInterface $normalizer){
        $entityManager = $doctrine->getManager();

        // Get the Offre based on the ID in the URL

       $projets = $projetsRepository->find($id);
        if (!$projets) {
            return new JsonResponse(['error' => 'projets not found'], Response::HTTP_NOT_FOUND);
        }
        
        
        $projets->setTheme($req->get('theme'));
        $projets->setDescription($req->get('description'));
        $projets-> setDuree($req->get('duree'));
        $projets->setDatedebut(new \DateTime($req->get('datedebut')));
        $projets->setDatefin(new \DateTime($req->get('datefin')));
        $projets->setNom($req->get('nom'));
        $projets->setNote(0);
        $secteur = $req->get('secteur');
        $projets->setIdsecteur($secrepo->findOneByDescription($secteur));
       $entityManager->persist($projets);
       $entityManager->flush();
        $this->addoffrejson($doctrine, $entityManager->getRepository(Projets::class)->find($projets->getIdprojet()), $typerepo, $userrepo, 67 , $normalizer);
   $jsoncontent = $normalizer->normalize($projets,'json',['groups' => 'public']);
   return new Response("Projet added with success: ".json_encode($jsoncontent));
      }




    #[Route('/api/deleteProjet/{id}', name: 'deleteOffre', methods: ['GET'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        // Get the Offre based on the ID in the URL
        $projetRepository = $entityManager->getRepository(Projets::class);
        $projet = $projetRepository->find($id);
        if (!$projet) {
            return new JsonResponse(['error' => 'Projet not found'], Response::HTTP_NOT_FOUND);
        }

        // Remove the Offre object from the database
        $entityManager->remove($projet);
        $entityManager->flush();

        // Return a JSON response to confirm that the Offre was deleted
        return new JsonResponse(['message' => 'Projet deleted successfully'],Response::HTTP_OK,[],false);
    }




}
