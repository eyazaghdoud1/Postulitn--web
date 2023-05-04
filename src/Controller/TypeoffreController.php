<?php

namespace App\Controller;

use App\Entity\Typeoffre;
use App\Form\TypeoffreType;
use App\Repository\TypeoffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typeoffre')]
class TypeoffreController extends AbstractController
{
    #[Route('/list', name: 'app_typeoffre_index', methods: ['GET'])]
    public function index(TypeoffreRepository $typeoffreRepository, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
        return $this->render('typeoffre/index.html.twig', [
            'typeoffres' => $typeoffreRepository->findAll(),
        ]);} else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/new', name: 'app_typeoffre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeoffreRepository $typeoffreRepository, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
        $typeoffre = new Typeoffre();
        $form = $this->createForm(TypeoffreType::class, $typeoffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeoffreRepository->save($typeoffre, true);

            return $this->redirectToRoute('app_typeoffre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeoffre/new.html.twig', [
            'typeoffre' => $typeoffre,
            'form' => $form,
        ]);} else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/{idtype}', name: 'app_typeoffre_show', methods: ['GET'])]
    public function show(Typeoffre $typeoffre, SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
        return $this->render('typeoffre/show.html.twig', [
            'typeoffre' => $typeoffre,
        ]);} else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/{idtype}/edit', name: 'app_typeoffre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typeoffre $typeoffre, TypeoffreRepository $typeoffreRepository,
    SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
        $form = $this->createForm(TypeoffreType::class, $typeoffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeoffreRepository->save($typeoffre, true);

            return $this->redirectToRoute('app_typeoffre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeoffre/edit.html.twig', [
            'typeoffre' => $typeoffre,
            'form' => $form,
        ]);} else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/{idtype}', name: 'app_typeoffre_delete', methods: ['POST'])]
    public function delete(Request $request, Typeoffre $typeoffre, TypeoffreRepository $typeoffreRepository,
    SessionInterface $session): Response
    {
        if ($session->get('user') && $session->get('user')->getIdrole()->getDescription()=='Administrateur'){
        if ($this->isCsrfTokenValid('delete'.$typeoffre->getIdtype(), $request->request->get('_token'))) {
            $typeoffreRepository->remove($typeoffre, true);
        }

        return $this->redirectToRoute('app_typeoffre_index', [], Response::HTTP_SEE_OTHER);
    } else {
        return $this->render('notfound.html.twig');
    }
}
}
