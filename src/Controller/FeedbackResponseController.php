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
use App\Service\FileUploader;
use App\Service\ActionLog;
use App\Repository\TableListRepository;
use Knp\Component\Pager\PaginatorInterface;


class FeedbackResponseController extends AbstractController
{

    /**
     * @Route("/feedbackresponse", name="feedbackresponse_show")
     */
    public function showAll(FeedbackResponseRepository $feedbackresponseRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $q = $request->query->get('q');
        $queryBuilder = $feedbackresponseRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great feedbackresponse: '.$feedbackresponse->getTemplateId()
        // or render a template
        // in the template, print things with {{ feedbackresponse.name }}
return $this->render('feedbackresponse/showAll.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/feedbackresponse/edit/{id}", name="feedbackresponse_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, FeedbackResponseRepository $feedbackresponseRepository, Request $request, FileUploader $fileUploader, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $feedbackresponse = $feedbackresponseRepository
            ->find($id);

        $feedbackresponse->getTemplate();
        $feedbackresponse->getApplicant();


        $form = $this->createForm(FeedbackResponseType::class, $feedbackresponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $pdfFile = $form->get('feedback')->getData();
        if ($pdfFile) {
            $pdfFileName = $fileUploader->uploadPDF($pdfFile);
            $feedbackresponse->setFeedback($pdfFileName);
        }

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

        $user = $this->getUser();
        $userid = $user->getId();
        $feedbackresponseid = $feedbackresponse->getId();
        $tablename = $tablelistRepository->findBy(array('name' => 'Feedback Response'),array('name' => 'ASC'),1 ,0)[0];
        $tablenameid = $tablename->getId();
        $actionLog->addAction("Added Feedback Response",$userid,$tablenameid,$feedbackresponseid);


        $RAW_QUERY = "UPDATE job_applicant SET applicantresponded = 1 WHERE applicant_id = '$applicantid' and job_id = '$jobid'";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

            return $this->redirectToRoute('template_display');
        }

        //return new Response('Check out this great feedbackresponse: '.$feedbackresponse->getTemplateId()
        // or render a template
        // in the template, print things with {{ feedbackresponse.name }}
        return $this->render('feedbackresponse/edit.html.twig', ['feedbackresponse' => $feedbackresponse,'form' => $form->createView()]);

}


}