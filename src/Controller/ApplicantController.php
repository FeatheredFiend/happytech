<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Form\ApplicantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApplicantController extends AbstractController
{

    /**
     * @Route("/applicant/create", name="applicant_create")
     */
    public function createApplicant(ValidatorInterface $validator, Request $request): Response
    {
        $applicant = new Applicant();

        $form = $this->createForm(ApplicantType::class, $applicant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($applicant);
            $em->flush();

            return $this->redirectToRoute('applicant_show');
        }
        return $this->render('applicant/add.html.twig', ['form' => $form->createView(),'applicant' => $applicant]);

    }

    /**
     * @Route("/applicant", name="applicant_show")
     */
    public function showAll(ApplicantRepository $applicantRepository): Response
    {
        $applicant = $applicantRepository
            ->findAll();

        //return new Response('Check out this great applicant: '.$applicant->getName()
        // or render a template
        // in the template, print things with {{ applicant.name }}
        return $this->render('applicant/showAll.html.twig', ['applicant' => $applicant]);
    }

    /**
     * @Route("/applicant/edit/{id}", name="applicant_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, ApplicantRepository $applicantRepository, Request $request): Response
    {
        $applicant = $applicantRepository
            ->find($id);

        $applicant->getName();
        $applicant->getEmail();


        $form = $this->createForm(ApplicantType::class, $applicant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($applicant);
            $em->flush();

            return $this->redirectToRoute('applicant_show');
        }

        //return new Response('Check out this great applicant: '.$applicant->getName()
        // or render a template
        // in the template, print things with {{ applicant.name }}
        return $this->render('applicant/edit.html.twig', ['applicant' => $applicant,'form' => $form->createView()]);

}


}