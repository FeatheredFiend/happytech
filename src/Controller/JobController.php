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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JobController extends AbstractController
{

    /**
     * @Route("/job/create", name="job_create")
     */
    public function createJob(ValidatorInterface $validator, Request $request): Response
    {
        $job = new Job();

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('job_show');
        }
        return $this->render('job/add.html.twig', ['form' => $form->createView(),'job' => $job]);

    }

    /**
     * @Route("/job", name="job_show")
     */
    public function showAll(JobRepository $jobRepository): Response
    {
        $job = $jobRepository
            ->findAll();

        //return new Response('Check out this great job: '.$job->getJobName()
        // or render a template
        // in the template, print things with {{ job.name }}
        return $this->render('job/showAll.html.twig', ['job' => $job]);
    }

    /**
     * @Route("/job/edit/{id}", name="job_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, JobRepository $jobRepository, Request $request): Response
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

            return $this->redirectToRoute('job_show');
        }

        //return new Response('Check out this great job: '.$job->getName()
        // or render a template
        // in the template, print things with {{ job.name }}
        return $this->render('job/edit.html.twig', ['job' => $job,'form' => $form->createView()]);

}


}