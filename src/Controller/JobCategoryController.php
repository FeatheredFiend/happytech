<?php

namespace App\Controller;

use App\Entity\JobCategory;
use App\Form\JobCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\JobCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JobCategoryController extends AbstractController
{

    /**
     * @Route("/jobcategory/create", name="jobcategory_create")
     */
    public function createJobCategory(ValidatorInterface $validator, Request $request): Response
    {
        $jobcategory = new JobCategory();

        $form = $this->createForm(JobCategoryType::class, $jobcategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobcategory);
            $em->flush();

            return $this->redirectToRoute('jobcategory_show');
        }
        return $this->render('jobcategory/add.html.twig', ['form' => $form->createView(),'jobcategory' => $jobcategory]);

    }

    /**
     * @Route("/jobcategory", name="jobcategory_show")
     */
    public function showAll(JobCategoryRepository $jobcategoryRepository): Response
    {
        $jobcategory = $jobcategoryRepository
            ->findAll();

        //return new Response('Check out this great jobcategory: '.$jobcategory->getName()
        // or render a template
        // in the template, print things with {{ jobcategory.name }}
        return $this->render('jobcategory/showAll.html.twig', ['jobcategory' => $jobcategory]);
    }

    /**
     * @Route("/jobcategory/edit/{id}", name="jobcategory_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, JobCategoryRepository $jobcategoryRepository, Request $request): Response
    {
        $jobcategory = $jobcategoryRepository
            ->find($id);



        $form = $this->createForm(JobCategoryType::class, $jobcategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobcategory);
            $em->flush();

            return $this->redirectToRoute('jobcategory_show');
        }

        //return new Response('Check out this great jobcategory: '.$jobcategory->getJobCategory()
        // or render a template
        // in the template, print things with {{ jobcategory.name }}
        return $this->render('jobcategory/edit.html.twig', ['jobcategory' => $jobcategory,'form' => $form->createView()]);

}


}