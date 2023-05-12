<?php

namespace App\Controller;

use App\Repository\GuidesentretiensRepository;
use App\Repository\ComptesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyWomponent\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface; 
use App\Entity\Guidesentretiens;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Comptes;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

class JSONController1 extends AbstractController
{
    #[Route("/allGuides", name: "listGuidesJSON")] 
    public function getGuides(GuidesentretiensRepository $repo, SerializerInterface $serializer)
    {
        $guides = $repo->findAll(); 
        $json=$serializer->serialize($guides, 'json', ['groups' => 'guides']);  
        return new Response($json); 
    }



    #[Route("/Guide/{id}", name: "guideJSON")] 
public function GuideId( Request $req,SerializerInterface $serializer, GuidesentretiensRepository $repo) 
{
$guide = $repo->find($req-> get("id")); 
$guideNormalises = $serializer->serialize($guide, 'json', ['groups' => "guides"]); 
return new Response($guideNormalises);
}




#[Route("addGuideJSON/new", name: "addGuideJSON")] 
public function addGuideJSON(Request $req,	serializerInterface $serializer) 
{
    $em = $this->getDoctrine()->getManager(); 
    $guide = new Guidesentretiens();
    $guide->setDomaine($req->get('domaine'));
    $guide->setSpecialite($req->get('specialite'));
    $guide->setSupport($req->get('support'));
    $em->persist($guide);
    $em->flush();
    

$jsonContent = $serializer->serialize($guide, 'json', ['groups' => 'guides']); 
return new Response(json_encode($jsonContent));

}

#[Route('/updateGuideJSON/{id}', name: 'updateGuideJSON')]
public function updateGuideJSON ( Request $req,ManagerRegistry $doctrine, SerializerInterface $serializer)
{
    $em = $doctrine->getManager();
    $guide = $em->getRepository(Guidesentretiens::class)->find($req->get('id'));
    $guide->setDomaine($req->get('domaine'));
    $guide->setSpecialite($req->get('specialite'));

    //$em->persist($candidature);
    $em->flush();

    $json = $serializer->serialize($guide, 'json', ['groups'=>'guides']);
    return new Response( $json);
       
}

#[Route('/deleteGuideJSON/{id}', name: 'deleteGuideJSON')]
public function deleteGuideJSON ( Request $req,ManagerRegistry $doctrine, SerializerInterface $serializer)
{
    $em = $doctrine->getManager();
    $guide = $em->getRepository(Guidesentretiens::class)->find($req->get('id'));
    
    $em->remove($guide);
    $em->flush();

    $json = $serializer->serialize($guide, 'json', ['groups'=>'guides']);
    return new Response( 'deleted'.$json);
       
}











#[Route("/allComptes", name: "listComtesJSON")] 
public function getCompte(ComptesRepository $repo, SerializerInterface $serializer)
{
    $comptes = $repo->findAll(); 
    $json=$serializer->serialize($comptes, 'json', ['groups' => 'comptes']);  
    return new Response($json); 
}



#[Route("/Compte/{id}", name: "compteJSON")] 
public function CompteId( Request $req,SerializerInterface $serializer, ComptesRepository $repo) 
{
$compte = $repo->findByUser($req-> get("id")); 
$compteNormalises = $serializer->serialize($compte, 'json', ['groups' => "comptes"]); 
return new Response($compteNormalises);
}




#[Route("addCompteJSON/new/{idutilisateur}", name: "addCompteJSON")] 
public function addCompteJSON(Request $req,	serializerInterface $serializer, UtilisateurRepository $repo ) 
{
$em = $this->getDoctrine()->getManager(); 
$compte = new Comptes();
$compte->setIdutilisateur($repo->find($req->get('idutilisateur')));
$compte->setPhoto($req->get('photo'));
$compte->setDiplome($req->get('diplome'));

$compte->setDomaine($req->get('domaine'));
$compte->setEntreprise($req->get('entreprise'));
$compte->setExperience($req->get('experience'));
$compte->setPoste($req->get('poste'));
$compte->setDatediplome(new \DateTime($req->get('dateDiplome')));


$em->persist($compte);
$em->flush();


$jsonContent = $serializer->serialize($compte, 'json', ['groups' => 'comptes']); 
return new Response(json_encode($jsonContent));

}

#[Route('/updateCompteJSON/{id}', name: 'updateCompteJSON')]
public function updateCompteJSON ( Request $req,ManagerRegistry $doctrine, SerializerInterface $serializer, $id,UtilisateurRepository $repo)
{
$em = $doctrine->getManager();
$compte = $em->getRepository(Comptes::class)->find($req->get('id'));
$compte->setPhoto($req->get('photo'));
$compte->setDiplome($req->get('diplome'));
$compte->setDomaine($req->get('domaine'));
$compte->setEntreprise($req->get('entreprise'));
$compte->setExperience($req->get('experience'));
$compte->setPoste($req->get('poste'));
$compte->setDatediplome(new \DateTime($req->get('dateDiplome')));


//$em->persist($candidature);
$em->flush();

$json = $serializer->serialize($compte, 'json', ['groups'=>'comptes']);
return new Response( $json);
   
}

#[Route('/deleteCompteJSON/{id}', name: 'deleteCompteJSON')]
public function deleteCompteJSON ( Request $req,ManagerRegistry $doctrine, SerializerInterface $serializer)
{
$em = $doctrine->getManager();
$guide = $em->getRepository(Comptes::class)->find($req->get('id'));

$em->remove($guide);
$em->flush();

$json = $serializer->serialize($guide, 'json', ['groups'=>'comptes']);
return new Response( 'deleted'.$json);
   
}



}
