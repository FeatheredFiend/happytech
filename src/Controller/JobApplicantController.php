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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;


class JobApplicantController extends AbstractController
{

    /**
     * @Route("/jobapplicant/create", name="jobapplicant_create")
     */
    public function createJobApplicant(ValidatorInterface $validator, Request $request): Response
    {


        $jobapplicant = new JobApplicant();

        $form = $this->createForm(JobApplicantType::class, $jobapplicant);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobapplicant);
            $em->flush();

            return $this->redirectToRoute('jobapplicant_show');
        }
        return $this->render('jobapplicant/add.html.twig', ['form' => $form->createView(),'jobapplicant' => $jobapplicant]);

    }

    /**
     * @Route("/jobapplicant", name="jobapplicant_show")
     */
    public function showAll(JobApplicantRepository $jobapplicantRepository): Response
    {
        $jobapplicant = $jobapplicantRepository
            ->findAll();

        //return new Response('Check out this great jobapplicant: '.$jobapplicant->getJobId()
        // or render a template
        // in the template, print things with {{ jobapplicant.name }}
return $this->render('jobapplicant/showAll.html.twig', array('jobapplicant' => $jobapplicant));

    }

    /**
     * @Route("/jobapplicant/edit/{id}", name="jobapplicant_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, JobApplicantRepository $jobapplicantRepository, Request $request): Response
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

            return $this->redirectToRoute('jobapplicant_show');
        }

        //return new Response('Check out this great jobapplicant: '.$jobapplicant->getJobId()
        // or render a template
        // in the template, print things with {{ jobapplicant.name }}
        return $this->render('jobapplicant/edit.html.twig', ['jobapplicant' => $jobapplicant,'form' => $form->createView()]);

}


}