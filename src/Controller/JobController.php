<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobCategory;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\JobRepository;
use App\Repository\TableListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class JobController extends AbstractController
{

    
    /**
     * @Route("/job", name="job_show")
     */
    public function showAll(JobRepository $jobRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $jobRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great job: '.$job->getJobName()
        // or render a template
        // in the template, print things with {{ job.name }}
        return $this->render('job/showAll.html.twig', ['pagination' => $pagination]);
    }


    /**
     * @Route("/job/create", name="job_create")
     */
    public function createJob(ValidatorInterface $validator, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $job = new Job();

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $jobid = $job->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Job'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Job",$userid,$tablenameid,$jobid);

            return $this->redirectToRoute('job_show');
        }
        return $this->render('job/add.html.twig', ['form' => $form->createView(),'job' => $job]);

    }

    /**
     * @Route("/job/edit/{id}", name="job_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, JobRepository $jobRepository, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $job = $jobRepository
            ->find($id);



        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $jobid = $job->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Job'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Job",$userid,$tablenameid,$jobid);

            return $this->redirectToRoute('job_show');
        }

        //return new Response('Check out this great job: '.$job->getName()
        // or render a template
        // in the template, print things with {{ job.name }}
        return $this->render('job/edit.html.twig', ['job' => $job,'form' => $form->createView()]);

}


}