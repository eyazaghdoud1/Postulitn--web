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
    public function listeRoles(RoleRepository $repo): Response
    {
        $roles = $repo->findAll();
        return $this->render('roles/index.html.twig', [
            'roles' => $roles
        ]);
    }

    #[Route('/addRole', name: 'addRole')]
    public function addRole(ManagerRegistry $doctrine, Request $req)
    {
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
    }

    #[Route('/deleteRole/{idRole}', name: 'deleteRole')]
    public function deleteRole($idRole, ManagerRegistry $doctrine)
    {
        $role = $doctrine->getRepository(Role::class)->find($idRole);
        $em = $doctrine->getManager();
        $em->remove($role);
        $em->flush();
        return $this->redirectToRoute('readRoles');
    }

    #[Route('/updateRole/{idRole}', name: 'updateRole')]
    public function updateRole(Request $req, $idRole, ManagerRegistry $doctrine)
    {
        $role = $doctrine->getRepository(Role::class)->find($idRole);
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('readRoles');
        }
        return $this->render('roles/updateRole.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
