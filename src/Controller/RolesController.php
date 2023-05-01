<?php

namespace App\Controller;

use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RolesController extends AbstractController
{

    #[Route('/roles', name: 'app_roles')]
    public function index(): Response
    {
        return $this->render('roles/index.html.twig', [
            'controller_name' => 'RolesController',
        ]);
    }

    #[Route('/RolesListe', name: 'readRoles')]
    public function listeRoles(RoleRepository $repo, SessionInterface $session): Response
    {
        if ($session->get('user')) {
            $roles = $repo->findAll();
            return $this->render('roles/index.html.twig', [
                'roles' => $roles
            ]);
        } else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/addRole', name: 'addRole')]
    public function addRole(ManagerRegistry $doctrine, Request $req, SessionInterface $session)
    {
        if ($session->get('user')) {
            $role = new Role();
            $form = $this->createForm(RoleType::class, $role);
            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->persist($role);
                $em->flush();
                return $this->redirectToRoute('readRoles');
            }

            return $this->render('roles/addRole.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/deleteRole/{idRole}', name: 'deleteRole')]
    public function deleteRole($idRole, ManagerRegistry $doctrine, SessionInterface $session)
    {
        if ($session->get('user')) {
            $role = $doctrine->getRepository(Role::class)->find($idRole);
            $em = $doctrine->getManager();
            $em->remove($role);
            $em->flush();
            return $this->redirectToRoute('readRoles');
        } else {
            return $this->render('notfound.html.twig');
        }
    }

    #[Route('/updateRole/{idRole}', name: 'updateRole')]
    public function updateRole(Request $req, $idRole, ManagerRegistry $doctrine, SessionInterface $session)
    {

        if ($session->get('user')) {
            $role = $doctrine->getRepository(Role::class)->find($idRole);
            $form = $this->createForm(RoleType::class, $role);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->flush();
                return $this->redirectToRoute('readRoles');
            }
            return $this->render('roles/updateRole.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('notfound.html.twig');
        }
    }
}
