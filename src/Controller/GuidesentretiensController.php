<?php

namespace App\Controller;

use App\Entity\Guidesentretiens;
use App\Form\GuidesentretiensType;
use App\Repository\GuidesentretiensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;





#[Route('/guidesentretiens')]
class GuidesentretiensController extends AbstractController
{
    #[Route('/', name: 'app_guidesentretiens_index', methods: ['GET'])]
    public function index(GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        return $this->render('guidesentretiens/index.html.twig', [
            'guidesentretiens' => $guidesentretiensRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_guidesentretiens_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        $guidesentretien = new Guidesentretiens();
        $form = $this->createForm(GuidesentretiensType::class, $guidesentretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guidesentretiensRepository->save($guidesentretien, true);
      
            return $this->redirectToRoute('app_guidesentretiens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guidesentretiens/new.html.twig', [
            'guidesentretien' => $guidesentretien,
            'form' => $form,
        ]);
    }

   /* #[Route('/guidesentretiens/{idGuide}', name: 'app_guidesentretiens_show', methods: ['GET'])]
public function show(
    #[ParamConverter('guidesentretien', class: Guidesentretiens::class)]
    Guidesentretiens $guidesentretien
): Response {
    return $this->render('guidesentretiens/show.html.twig', [
        'guidesentretien' => $guidesentretien,
    ]);
}
    */


    #[Route('/{idguide}', name: 'app_guidesentretiens_show', methods: ['GET'])]
    public function show(Guidesentretiens $guidesentretien): Response
    {
        return $this->render('guidesentretiens/show.html.twig', [
            'guidesentretien' => $guidesentretien,
        ]);
    }

    #[Route('/{idguide}/edit', name: 'app_guidesentretiens_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guidesentretiens $guidesentretien, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        $form = $this->createForm(GuidesentretiensType::class, $guidesentretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guidesentretiensRepository->save($guidesentretien, true);

            return $this->redirectToRoute('app_guidesentretiens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guidesentretiens/edit.html.twig', [
            'guidesentretien' => $guidesentretien,
            'form' => $form,
        ]);
    }

    #[Route('/{idguide}', name: 'app_guidesentretiens_delete', methods: ['POST'])]
    public function delete(Request $request, Guidesentretiens $guidesentretien, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guidesentretien->getIdguide(), $request->request->get('_token'))) {
            $guidesentretiensRepository->remove($guidesentretien, true);
        }

        return $this->redirectToRoute('app_guidesentretiens_index', [], Response::HTTP_SEE_OTHER);
    }
}
