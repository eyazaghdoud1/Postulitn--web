<?php

namespace App\Controller;

use App\Entity\Guidesentretiens;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Form\GuidesentretiensType;
use App\Repository\GuidesentretiensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Range;
use Embed\Embed;
use Symfony\Contracts\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\HttpFoundation\Session\SessionInterface;







#[Route('/guidesentretiens')]
class GuidesentretiensController extends AbstractController
{
    #[Route('/listeGuideBack', name: 'app_guidesentretiens_index', methods: ['GET'])]
    public function index(GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        return $this->render('guidesentretiens/index.html.twig', [
            'guidesentretiens' => $guidesentretiensRepository->findAll(),
        ]);
    }

    
#[Route('/listeGuideUser', name: 'app_guidesentretiens_indexu', methods: ['GET'])]
public function indexu(GuidesentretiensRepository $guidesentretiensRepository): Response
{
    return $this->render('guidesentretiens/indexu.html.twig', [
        'guidesentretiens' => $guidesentretiensRepository->findAll(),
    ]);

}


  
    #[Route('/new', name: 'app_guidesentretiens_new', methods: ['GET', 'POST'])]
    public function new(ParameterBagInterface $parameterBag,Request $request,SluggerInterface $slugger, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        $guidesentretien = new Guidesentretiens();
        $form = $this->createForm(GuidesentretiensType::class, $guidesentretien);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           /* $imageFile = $form->get('imageFile')->getData();
            // Check if an image file has been uploaded
            if ($imageFile) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                // Update the 'image' property of the product entity
                $guidesentretien->setImageName($fileName);
            }*/
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

    
    #[Route('/ShowU/{idguide}', name: 'app_guidesentretiens_showu', methods: ['GET'])]
    public function showU(Guidesentretiens $guidesentretien): Response
    {
        return $this->render('guidesentretiens/showu.html.twig', [
            'guidesentretien' => $guidesentretien,
        ]);
    }
/* 
    #[Route('/u{idguide}', name: 'app_guidesentretiensu_show', methods: ['GET'])]  public function showu(Guidesentretiens $guidesentretien): Response
    {
        return $this->render('guidesentretiens/showu.html.twig', [
            'guidesentretien' => $guidesentretien,
        ]);
    }
*/


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



    
        /**
         * @Route("/guidesentretiens/{idguide}/set-note", name="app_guidesentretiens_set_note", methods={"POST"})
         */
        public function setNote(Request $request, Guidesentretiens $guidesentretien, SessionInterface $session): Response
    {
        $note = (float) $request->request->get('note');
    
        $nombrenotes = $guidesentretien->getNombrenotes() + 1;
        $currentNoteTotal = $guidesentretien->getNote() * ($nombrenotes - 1);
        $newNoteTotal = $currentNoteTotal + $note;
        
        $guidesentretien->setNombrenotes($nombrenotes);
        $guidesentretien->setNote($newNoteTotal / $nombrenotes);
        
        $this->getDoctrine()->getManager()->flush();
    
        $this->addFlash('success', 'La note a été mise à jour avec succès.');
        
        return $this->redirectToRoute('app_guidesentretiens_showu', ['idguide' => $guidesentretien->getIdguide()]);
    }
    




/*
 * @Route("/{id}/download", name="guidesentretiens_download", methods={"GET"})


public function download(GuideSentretiens $guideSentretiens): Response
{
    $file = new File($this->getParameter('kernel.project_dir').'/public/uploads/Guidesentretiens/'.$guideSentretiens->getSupport());
    
    return $downloadHandler->downloadObject($guideSentretiens, $file, 'supportFile');
}
 */




public function lireFichier()
{
    $chemin = 'C:\Users\dell\AppData\Local\Temp\php996.tmp';

    // Lire le contenu du fichier
    $contenu = file_get_contents($chemin);

    return $this->render('mon_template.html.twig', [
        'contenu' => $contenu
    ]);
}


#[Route('/unew', name: 'app_guidesentretiensu_new', methods: ['GET', 'POST'])]
public function newu(ParameterBagInterface $parameterBag,Request $request,SluggerInterface $slugger, GuidesentretiensRepository $guidesentretiensRepository): Response
{
    $guidesentretien = new Guidesentretiens();
    $form = $this->createForm(GuidesentretiensType::class, $guidesentretien);
    $form->handleRequest($request);


    

    if ($form->isSubmitted() && $form->isValid()) {

       /* $imageFile = $form->get('imageFile')->getData();

        // Check if an image file has been uploaded
        if ($imageFile) {
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            // Update the 'image' property of the product entity
            $guidesentretien->setImageName($fileName);
        }*/
        $guidesentretiensRepository->save($guidesentretien, true);

        return $this->redirectToRoute('app_guidesentretiensu_index', [], Response::HTTP_SEE_OTHER);
    }
   

    return $this->renderForm('guidesentretiens/unew.html.twig', [
        'guidesentretien' => $guidesentretien,
        'form' => $form,
    ]);
}





#[Route('/{idguide}/editu', name: 'app_guidesentretiensu_edit', methods: ['GET', 'POST'])]
    public function editu(Request $request, Guidesentretiens $guidesentretien, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        $form = $this->createForm(GuidesentretiensType::class, $guidesentretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guidesentretiensRepository->save($guidesentretien, true);

            return $this->redirectToRoute('app_guidesentretiensu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guidesentretiens/edit.html.twig', [
            'guidesentretien' => $guidesentretien,
            'form' => $form,
        ]);
    }


    #[Route('/u{idguide}', name: 'app_guidesentretiensu_delete', methods: ['POST'])]
    public function deleteu(Request $request, Guidesentretiens $guidesentretien, GuidesentretiensRepository $guidesentretiensRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guidesentretien->getIdguide(), $request->request->get('_token'))) {
            $guidesentretiensRepository->remove($guidesentretien, true);
        }

        return $this->redirectToRoute('app_guidesentretiensu_index', [], Response::HTTP_SEE_OTHER);
    }



    public function setNoteu(Request $request, Guidesentretiens $guidesentretien, SessionInterface $session): Response
    {
        $note = (float) $request->request->get('note');
    
        $nombrenotes = $guidesentretien->getNombrenotes() + 1;
        $currentNoteTotal = $guidesentretien->getNote() * ($nombrenotes - 1);
        $newNoteTotal = $currentNoteTotal + $note;
        
        $guidesentretien->setNombrenotes($nombrenotes);
        $guidesentretien->setNote($newNoteTotal / $nombrenotes);
        
        $this->getDoctrine()->getManager()->flush();
    
        $this->addFlash('success', 'La note a été mise à jour avec succès.');
        
        return $this->redirectToRoute('app_guidesentretiensu_show', ['idguide' => $guidesentretien->getIdguide()]);
    }





    public function lireFichieru()
    {
        $chemin = 'C:\Users\dell\AppData\Local\Temp\php996.tmp';
    
        // Lire le contenu du fichier
        $contenu = file_get_contents($chemin);
    
        return $this->render('mon_template.html.twig', [
            'contenu' => $contenu
        ]);
    }




    
    }




    
    




