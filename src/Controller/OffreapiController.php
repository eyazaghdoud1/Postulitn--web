<?php

namespace App\Controller;

use App\Controller\OffreController as ControllerOffreController;
use App\Entity\Utilisateur;
use DateTime;
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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;

class OffreapiController extends AbstractController{

    #[Route('/api/offres/all/{id}', name: 'app_offre_all_exceptApi' )]
    public function indexAll(int $id,SerializerInterface $serializer, OffreRepository $offreRepository, Request $request): Response
    {

        $offres = $offreRepository->findAllExceptUser($id);
        $data = $serializer->serialize($offres, 'json', ['groups' => 'public']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
    #[Route('/api/offres/all/', name: 'app_offre_all')]
    public function index(SerializerInterface $serializer, OffreRepository $offreRepository, Request $request): Response
    {


        $offres = $offreRepository->findAll();
        $data = $serializer->serialize($offres, 'json', ['groups' => 'public']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }


    #[Route('/api/offres/user/{id}', name: 'app_user_offresApi')]
    public function getUserOffres(SerializerInterface $serializer, OffreRepository $offreRepository, Request $request, TypeoffreRepository $typerepo, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $offres = $offreRepository->findBy(['idrecruteur' => $user]);
        $data = $serializer->serialize($offres, 'json', ['groups' => 'public']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/createOffre/{id}', name: 'createOffre1', methods: ['POST'])]
    public function create(SerializerInterface $serializer, TypeoffreRepository $typerepo, int $id, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        // Get the user based on the ID in the URL
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        $user = $userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Get the request data from URL params
        $poste = $request->query->get('poste');
        $description = $request->query->get('description');
        $lieu = $request->query->get('lieu');
        $entreprise = $request->query->get('entreprise');
        $specialite = $request->query->get('specialite');
        $date_expiration = $request->query->get('dateExpiration');
        $idType = $request->query->get('idType');

        // Create a new Offre object based on the request data
        $offre = new Offre();
        $offre->setPoste($poste);
        $offre->setDescription($description);
        $offre->setLieu($lieu);
        $offre->setEntreprise($entreprise);
        $offre->setSpecialite($specialite);
        $offre->setDateExpiration(new DateTime($date_expiration));

        $type_offre = $typerepo->findOneBy(['description' => $idType]);

        $offre->setIdtype($type_offre);
        $offre->setIdrecruteur($user);

        // Save the new Offre object to the database
        $entityManager->persist($offre);
        $entityManager->flush();

        $data = $serializer->serialize($offre, 'json', ['groups' => 'public']);
        // Return a JSON response with the new Offre object
        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }


    #[Route('/api/updateOffre/{id}', name: 'updateOffreApi', methods: ['POST'])]
    public function update(SerializerInterface $serializer, TypeoffreRepository $typerepo,int $id, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        // Get the Offre based on the ID in the URL
        $offreRepository = $entityManager->getRepository(Offre::class);
        $offre = $offreRepository->find($id);
        if (!$offre) {
            return new JsonResponse(['error' => 'Offre not found'], Response::HTTP_NOT_FOUND);
        }


        // Get the request data from URL params
        $poste = $request->query->get('poste');
        $description = $request->query->get('description');
        $lieu = $request->query->get('lieu');
        $entreprise = $request->query->get('entreprise');
        $specialite = $request->query->get('specialite');
        $date_expiration = $request->query->get('dateExpiration');
        $idType = $request->query->get('idType');
        // Parse the JSON request body

        // Create a new Offre object based on the request data
        $offre = new Offre();
        $offre->setPoste($poste);
        $offre->setDescription($description);
        $offre->setLieu($lieu);
        $offre->setEntreprise($entreprise);
        $offre->setSpecialite($specialite);
        $offre->setDateExpiration(new DateTime($date_expiration));

        $type_offre = $typerepo->findOneBy(['description' => $idType]);

        $offre->setIdtype($type_offre);


        // Save the new Offre object to the database
        //$entityManager->persist($offre);
        $entityManager->flush();


        $data = $serializer->serialize($offre, 'json', ['groups' => 'public']);
        // Return a JSON response with the new Offre object
        return new JsonResponse($data, Response::HTTP_OK, [], true);


    }


    #[Route('/api/deleteOffre/{id}', name: 'deleteOffreApi', methods: ['GET'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        // Get the Offre based on the ID in the URL
        $offreRepository = $entityManager->getRepository(Offre::class);
        $offre = $offreRepository->find($id);
        if (!$offre) {
            return new JsonResponse(['error' => 'Offre not found'], Response::HTTP_NOT_FOUND);
        }

        // Remove the Offre object from the database
        $entityManager->remove($offre);
        $entityManager->flush();

        // Return a JSON response to confirm that the Offre was deleted
        return new JsonResponse(['message' => 'Offre deleted successfully'],Response::HTTP_OK,[],false);
    }


    #[Route('/api/typeoffre/all', name: 'typeOffreApi')]
    public function getAllTypesOffres(SerializerInterface $serializer,TypeoffreRepository $typeoffreRepository, Request $request,  TypeoffreRepository $typerepo): Response
    {
        $type_offres = $typeoffreRepository->findAll();
        $data = $serializer->serialize($type_offres, 'json', ['groups' => 'public']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);


    }


}