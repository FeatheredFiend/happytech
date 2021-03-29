<?php

namespace App\Controller;

use App\Entity\Template;
use App\Entity\Applicant;
use App\Entity\FeedbackResponse;
use App\Entity\TemplateHeader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\TemplateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\TemplateRepository;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TemplateController extends AbstractController
{

    /**
     * @Route("/template/create", name="template_create")
     */
    public function createTemplate(ValidatorInterface $validator, Request $request): Response
    {


        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            return $this->redirectToRoute('template_show');
        }
        return $this->render('template/add.html.twig', ['form' => $form->createView(),'template' => $template]);

    }

    /**
     * @Route("/template", name="template_show")
     */
    public function showAll(TemplateRepository $templateRepository): Response
    {
        $template = $templateRepository
            ->findAll();

        //return new Response('Check out this great template: '.$template->getJobId()
        // or render a template
        // in the template, print things with {{ template.name }}
return $this->render('template/showAll.html.twig', array('template' => $template));

    }

    /**
     * @Route("/template/edit/{id}", name="template_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, TemplateRepository $templateRepository, Request $request): Response
    {
        $template = $templateRepository
            ->find($id);


        $form = $this->createForm(TemplateType::class, $template);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            return $this->redirectToRoute('template_show');
        }

        //return new Response('Check out this great template: '.$template->getJobId()
        // or render a template
        // in the template, print things with {{ template.name }}
        return $this->render('template/edit.html.twig', ['template' => $template,'form' => $form->createView()]);

}

    /**
     * @Route("/template/select/{name}", name="template_select")
     */
    public function select(TemplateRepository $templateRepository, string $name): Response
    {
        $template = $this->getDoctrine()
            ->getRepository(Template::class)
            ->findBy(array('decommissioned' => '0'),array('id' => 'ASC'));
$session = new Session();
$session->start();

// set and get session attributes
$session->set('applicantid', $name);
        //return new Response('Check out this great template: '.$template->getJobId()
        // or render a template
        // in the template, print things with {{ template.name }}
return $this->render('template/select.html.twig', array('template' => $template));

    }

    /**
     * @Route("/template/use/{id}", name="template_use", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function use(int $id, TemplateRepository $templateRepository, ApplicantRepository $applicantRepository, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        $template_query = "SELECT template.id, template.name, user.name AS user, feedback_type.name AS feedback FROM template LEFT JOIN template_header ON template.header_id = template_header.id LEFT JOIN feedback_type ON template_header.feedbacktype_id = feedback_type.id LEFT JOIN user ON template_header.user_id = user.id WHERE template.id = '$id' and template.decommissioned = 0 and template_header.decommissioned = 0 and user.decommissioned = 0 and feedback_type.decommissioned = 0";
        $templatequery = $em->getConnection()->prepare($template_query);
        $templatequery->execute();

        $result_template = $templatequery->fetchAll();
$session = new Session();
$session->start();
$jobid = $session->get('jobid');
$applicantid = $session->get('applicantid');
$session->set('template', $id);


        $applicant = $this->getDoctrine()
            ->getRepository(Applicant::class)
            ->findBy(array('name' => $applicantid),array('name' => 'ASC'),1 ,0)[0];

        $applicant = $applicant->getId();


        $feedbackcheck_query = "SELECT id, template_id, applicant_id, comment, job_id FROM feedback_response WHERE template_id = '$id' and applicant_id = '$applicant' and job_id = '$jobid'";
        $feedbackcheckquery = $em->getConnection()->prepare($feedbackcheck_query);
        $feedbackcheckquery->execute();

        $result_feedbackcheck = $feedbackcheckquery->fetchAll();


if ($result_feedbackcheck) {
} else {
        $feedbackresponse_query = "INSERT INTO feedback_response(template_id, applicant_id, comment, job_id) VALUES ('$id','$applicant','','$jobid')";
        $feedbackresponsequery = $em->getConnection()->prepare($feedbackresponse_query);
       	$feedbackresponsequery->execute();
}


        $applicantjob_query = "SELECT applicant.name AS applicantname, job.name AS jobname FROM applicant LEFT JOIN job_applicant ON job_applicant.applicant_id = applicant.id LEFT JOIN job ON job_applicant.job_id = job.id WHERE job.id = '$jobid' AND applicant.name = '$applicantid' and applicant.decommissioned = 0 and job.decommissioned = 0";
        $applicantjobquery = $em->getConnection()->prepare($applicantjob_query);
        $applicantjobquery->execute();

        $result_applicantjob = $applicantjobquery->fetchAll();

        $statement_query = "SELECT DISTINCT statement.id, statement.statement, IF(template.name = 0, 'Selected','Unselected') as name FROM statement LEFT JOIN template_statement ON template_statement.statement_id = statement.id LEFT JOIN template ON template_statement.template_id = template.id WHERE template.id = '$id' and template.decommissioned = 0 and statement.decommissioned = 0 UNION SELECT DISTINCT statement.id, statement.statement, IF(template.name = 0, 'Selected','Unselected') as name FROM statement LEFT JOIN template_statement ON template_statement.statement_id = statement.id LEFT JOIN template ON template_statement.template_id = template.id WHERE statement.decommissioned = 0 ORDER BY id ASC";
        $statementquery = $em->getConnection()->prepare($statement_query);
        $statementquery->execute();

        $result_statement = $statementquery->fetchAll();


        $response_query = "SELECT id, template_id, applicant_id, comment, job_id FROM feedback_response WHERE template_id = '$id' and applicant_id = '$applicant' and job_id = '$jobid'";
        $responsequery = $em->getConnection()->prepare($response_query);
        $responsequery->execute();

        $result_response = $responsequery->fetchAll();

        return $this->render('template/use.html.twig', ['template' => $result_template, 'statement' => $result_statement, 'applicantjob' => $result_applicantjob, 'response' => $result_response]);

    }


}