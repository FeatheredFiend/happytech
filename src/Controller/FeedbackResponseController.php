<?php

namespace App\Controller;

use App\Entity\FeedbackResponse;
use App\Entity\Template;
use App\Entity\Applicant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\FeedbackResponseType;
use App\Form\FeedbackResponseSelectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\FeedbackResponseRepository;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FeedbackResponseController extends AbstractController
{


    /**
     * @Route("/feedbackresponse/create", name="feedbackresponse_create")
     */
    public function createFeedbackResponse(ValidatorInterface $validator, Request $request): Response
    {


        $feedbackresponse = new FeedbackResponse();

        $form = $this->createForm(FeedbackResponseType::class, $feedbackresponse);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedbackresponse);
            $em->flush();

            return $this->redirectToRoute('feedbackresponse_show');
        }
        return $this->render('feedbackresponse/add.html.twig', ['form' => $form->createView(),'feedbackresponse' => $feedbackresponse]);

    }

    /**
     * @Route("/feedbackresponse/select", name="feedbackresponse_select")
     */
    public function selectFeedbackResponse(ValidatorInterface $validator, Request $request, FeedbackResponseRepository $feedbackresponseRepository): Response
    {
$session = $request->getSession();
$session->start();
$template = $session->get('template');
$applicantid = $session->get('applicantid');
$jobid = $session->get('jobid');

        $template = $this->getDoctrine()
            ->getRepository(Template::class)
            ->find($template);

        $applicant = $this->getDoctrine()
            ->getRepository(Applicant::class)
            ->findBy(array('name' => $applicantid),array('name' => 'ASC'),1 ,0)[0];

        $applicantid = $applicant->getId();

        $feedbackresponse =  new FeedbackResponse();


        $form = $this->createForm(FeedbackResponseType::class, $feedbackresponse);
$form->get('template')->setData($template);
$form->get('applicant')->setData($applicant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedbackresponse);
            $em->flush();

        $RAW_QUERY = "UPDATE job_applicant SET applicantresponded = 1 WHERE applicant_id = '$applicantid' and job_id = '$jobid'";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();


            return $this->redirectToRoute('homepage');
        }
        return $this->render('feedbackresponse/edit.html.twig', ['form' => $form->createView(),'feedbackresponse' => $feedbackresponse]);

    }

    /**
     * @Route("/feedbackresponse", name="feedbackresponse_show")
     */
    public function showAll(FeedbackResponseRepository $feedbackresponseRepository): Response
    {
        $feedbackresponse = $feedbackresponseRepository
            ->findAll();

        //return new Response('Check out this great feedbackresponse: '.$feedbackresponse->getTemplateId()
        // or render a template
        // in the template, print things with {{ feedbackresponse.name }}
return $this->render('feedbackresponse/showAll.html.twig', array('feedbackresponse' => $feedbackresponse));

    }

    /**
     * @Route("/feedbackresponse/edit/{id}", name="feedbackresponse_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, FeedbackResponseRepository $feedbackresponseRepository, Request $request): Response
    {
        $feedbackresponse = $feedbackresponseRepository
            ->find($id);

        $feedbackresponse->getTemplate();
        $feedbackresponse->getApplicant();


        $form = $this->createForm(FeedbackResponseType::class, $feedbackresponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedbackresponse);
            $em->flush();

$session = $request->getSession();
$session->start();
$applicantid = $session->get('applicantid');
$jobid = $session->get('jobid');


        $applicant = $this->getDoctrine()
            ->getRepository(Applicant::class)
            ->findBy(array('name' => $applicantid),array('name' => 'ASC'),1 ,0)[0];

        $applicantid = $applicant->getId();



        $RAW_QUERY = "UPDATE job_applicant SET applicantresponded = 1 WHERE applicant_id = '$applicantid' and job_id = '$jobid'";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

            return $this->redirectToRoute('feedbackresponse_show');
        }

        //return new Response('Check out this great feedbackresponse: '.$feedbackresponse->getTemplateId()
        // or render a template
        // in the template, print things with {{ feedbackresponse.name }}
        return $this->render('feedbackresponse/edit.html.twig', ['feedbackresponse' => $feedbackresponse,'form' => $form->createView()]);

}


}