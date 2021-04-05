<?php

namespace App\Controller;

use App\Entity\TemplateHeader;
use App\Entity\User;
use App\Entity\FeedbackType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\TemplateHeaderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\TemplateHeaderRepository;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\TableListRepository;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class TemplateHeaderController extends AbstractController
{
    
    /**
     * @Route("/templateheader", name="templateheader_show")
     */
    public function showAll(TemplateHeaderRepository $templateheaderRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $templateheaderRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great templateheader: '.$templateheader->getJobId()
        // or render a template
        // in the template, print things with {{ templateheader.name }}
return $this->render('templateheader/showAll.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/templateheader/create", name="templateheader_create")
     */
    public function createTemplateHeader(ValidatorInterface $validator, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {


        $templateheader = new TemplateHeader();

        $form = $this->createForm(TemplateHeaderType::class, $templateheader);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($templateheader);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $templateheaderid = $templateheader->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template Header'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Template Header",$userid,$tablenameid,$templateheaderid);

            return $this->redirectToRoute('templateheader_show');
        }
        return $this->render('templateheader/add.html.twig', ['form' => $form->createView(),'templateheader' => $templateheader]);

    }


    /**
     * @Route("/templateheader/edit/{id}", name="templateheader_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, TemplateHeaderRepository $templateheaderRepository, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {
        $templateheader = $templateheaderRepository
            ->find($id);

 
        $form = $this->createForm(TemplateHeaderType::class, $templateheader);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($templateheader);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $templateheaderid = $templateheader->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template Header'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Template Header",$userid,$tablenameid,$templateheaderid);

            return $this->redirectToRoute('templateheader_show');
        }

        //return new Response('Check out this great templateheader: '.$templateheader->getJobId()
        // or render a template
        // in the template, print things with {{ templateheader.name }}
        return $this->render('templateheader/edit.html.twig', ['templateheader' => $templateheader,'form' => $form->createView()]);

}


}