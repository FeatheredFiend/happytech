<?php

namespace App\Controller;

use App\Entity\JobApplicant;
use App\Entity\Job;
use App\Entity\Applicant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\JobApplicantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\JobApplicantRepository;
use App\Repository\ApplicantRepository;
use App\Repository\TableListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class JobApplicantController extends AbstractController
{


    /**
     * @Route("/jobapplicant", name="jobapplicant_show")
     */
    public function showAll(JobApplicantRepository $jobapplicantRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $q = $request->query->get('q');
        $queryBuilder = $jobapplicantRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great jobapplicant: '.$jobapplicant->getJobId()
        // or render a template
        // in the template, print things with {{ jobapplicant.name }}
return $this->render('jobapplicant/showAll.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/jobapplicant/create", name="jobapplicant_create")
     */
    public function createJobApplicant(ValidatorInterface $validator, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {


        $jobapplicant = new JobApplicant();

        $form = $this->createForm(JobApplicantType::class, $jobapplicant);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobapplicant);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $jobapplicantid = $jobapplicant->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Job Applicant'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Job Applicant",$userid,$tablenameid,$jobapplicantid);

            return $this->redirectToRoute('jobapplicant_show');
        }
        return $this->render('jobapplicant/add.html.twig', ['form' => $form->createView(),'jobapplicant' => $jobapplicant]);

    }


    /**
     * @Route("/jobapplicant/edit/{id}", name="jobapplicant_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, JobApplicantRepository $jobapplicantRepository, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $jobapplicant = $jobapplicantRepository
            ->find($id);

        $jobapplicant->getJob();
        $jobapplicant->getApplicant();
        $jobapplicant->getApplicantResponded();

        $form = $this->createForm(JobApplicantType::class, $jobapplicant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobapplicant);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $jobapplicantid = $jobapplicant->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Job Applicant'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Job Applicant",$userid,$tablenameid,$jobapplicantid);

            return $this->redirectToRoute('jobapplicant_show');
        }

        //return new Response('Check out this great jobapplicant: '.$jobapplicant->getJobId()
        // or render a template
        // in the template, print things with {{ jobapplicant.name }}
        return $this->render('jobapplicant/edit.html.twig', ['jobapplicant' => $jobapplicant,'form' => $form->createView()]);

}


}