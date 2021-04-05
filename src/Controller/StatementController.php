<?php

namespace App\Controller;

use App\Entity\FeedbackResponse;
use App\Entity\Statement;
use App\Entity\Applicant;
use App\Form\StatementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ApplicantRepository;
use App\Repository\StatementRepository;
use App\Repository\FeedbackResponseRepository;
use App\Repository\TableListRepository;
use App\Repository\FeedbackResponseStatementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;


class StatementController extends AbstractController
{

    /**
     * @Route("/statement", name="statement_show")
     */
    public function showAll(StatementRepository $statementRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $statementRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        //return new Response('Check out this great statement: '.$statement->getName()
        // or render a template
        // in the template, print things with {{ statement.name }}
        return $this->render('statement/showAll.html.twig', ['pagination' => $pagination]);
    }

    
    /**
     * @Route("/statement/create", name="statement_create")
     */
    public function createStatement(ValidatorInterface $validator, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {
        $statement = new Statement();

        $form = $this->createForm(StatementType::class, $statement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($statement);
            $em->flush();

            
            $user = $this->getUser();
            $userid = $user->getId();
            $statementid = $statement->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Statement'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Statement",$userid,$tablenameid,$statementid);

            return $this->redirectToRoute('statement_show');
        }
        return $this->render('statement/add.html.twig', ['form' => $form->createView(),'statement' => $statement]);

    }
    
    /**
     * @Route("/statement/edit/{id}", name="statement_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, StatementRepository $statementRepository, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {
        $statement = $statementRepository
            ->find($id);

        $statement->getStatement();



        $form = $this->createForm(StatementType::class, $statement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($statement);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $statementid = $statement->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Statement'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Statement",$userid,$tablenameid,$statementid);

            return $this->redirectToRoute('statement_show');
        }

        //return new Response('Check out this great statement: '.$statement->getStatement()
        // or render a template
        // in the template, print things with {{ statement.name }}
        return $this->render('statement/edit.html.twig', ['statement' => $statement,'form' => $form->createView()]);

     }

 
    /**
     * @Route("/statement/select/{id}", name="statement_select", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function open(int $id, FeedbackResponseRepository $feedbackresponseRepository, ActionLog $actionLog,TableListRepository $tablelistRepository, FeedbackResponseStatementRepository $feedbackresponsestatementRepository) :Response
    {

        $em = $this->getDoctrine()->getManager();
$session = new Session();
$session->start();

// set and get session attributes
$template = $session->get('template');
$jobid = $session->get('jobid');
$applicantid = $session->get('applicantid');

        $applicant = $this->getDoctrine()
            ->getRepository(Applicant::class)
            ->findBy(array('name' => $applicantid),array('name' => 'ASC'),1 ,0)[0];

        $applicantid = $applicant->getId();

        $feedback = $this->getDoctrine()
            ->getRepository(FeedbackResponse::class)
            ->findBy(array('template' => $template,'applicant' => $applicantid,'job' => $jobid),array('id' => 'ASC'),1 ,0)[0];

        $feedbackid = $feedback->getId();

        $feedbackcheck_query = "SELECT id, feedbackresponse_id, statement_id FROM feedback_response_statement WHERE feedbackresponse_id = '$feedbackid' and statement_id = '$id'";
        $feedbackcheckquery = $em->getConnection()->prepare($feedbackcheck_query);
        $feedbackcheckquery->execute();

        $result_feedbackcheck = $feedbackcheckquery->fetchAll();
        if ($result_feedbackcheck) {
        } else {
            if ($feedbackid > 0) {
                
                    $RAW_QUERY = "INSERT INTO feedback_response_statement(feedbackresponse_id, statement_id) VALUES ('$feedbackid','$id')";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();

                    $user = $this->getUser();
                    $userid = $user->getId();
                    $feedbackresponsestatement = $feedbackresponsestatementRepository->findBy(array('feedbackresponse' => $feedbackid, 'statement' => $id),array('id' => 'ASC'),1 ,0)[0];
                    $feedbackresponsestatementid = $feedbackresponsestatement->getId();
                    $tablename = $tablelistRepository->findBy(array('name' => 'Feedback Response Statement'),array('name' => 'ASC'),1 ,0)[0];
                    $tablenameid = $tablename->getId();
                    $actionLog->addAction("Selected Statement",$userid,$tablenameid,$feedbackresponsestatementid);
            } else {
            }
}

return new RedirectResponse('/template/use/'.$template);
}


}