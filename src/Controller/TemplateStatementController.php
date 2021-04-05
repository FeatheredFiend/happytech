<?php

namespace App\Controller;

use App\Entity\TemplateStatement;
use App\Entity\Template;
use App\Entity\Statement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\TemplateStatementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\TemplateStatementRepository;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\TableListRepository;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class TemplateStatementController extends AbstractController
{
    
    /**
     * @Route("/templatestatement", name="templatestatement_show")
     */
    public function showAll(TemplateStatementRepository $templatestatementRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $templatestatementRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great templatestatement: '.$templatestatement->getJobId()
        // or render a template
        // in the template, print things with {{ templatestatement.name }}
return $this->render('templatestatement/showAll.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/templatestatement/create", name="templatestatement_create")
     */
    public function createTemplateStatement(ValidatorInterface $validator, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {


        $templatestatement = new TemplateStatement();

        $form = $this->createForm(TemplateStatementType::class, $templatestatement);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($templatestatement);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $templatestatementid = $templatestatement->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template Statement'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Template Statement",$userid,$tablenameid,$templatestatementid);

            return $this->redirectToRoute('templatestatement_show');
        }
        return $this->render('templatestatement/add.html.twig', ['form' => $form->createView(),'templatestatement' => $templatestatement]);

    }


    /**
     * @Route("/templatestatement/edit/{id}", name="templatestatement_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, TemplateStatementRepository $templatestatementRepository, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {
        $templatestatement = $templatestatementRepository
            ->find($id);


        $form = $this->createForm(TemplateStatementType::class, $templatestatement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($templatestatement);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $templatestatementid = $templatestatement->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template Statement'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Template Statement",$userid,$tablenameid,$templatestatementid);

            return $this->redirectToRoute('templatestatement_show');
        }

        //return new Response('Check out this great templatestatement: '.$templatestatement->getJobId()
        // or render a template
        // in the template, print things with {{ templatestatement.name }}
        return $this->render('templatestatement/edit.html.twig', ['templatestatement' => $templatestatement,'form' => $form->createView()]);

}


}