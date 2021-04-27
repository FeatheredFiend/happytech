<?php

namespace App\Controller;

use App\Entity\Template;
use App\Entity\Applicant;
use App\Entity\Job;
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
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\TableListRepository;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TemplateController extends AbstractController
{
    
    /**
     * @Route("/template", name="template_show")
     */
    public function showAll(TemplateRepository $templateRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $templateRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //return new Response('Check out this great template: '.$template->getJobId()
        // or render a template
        // in the template, print things with {{ template.name }}
return $this->render('template/showAll.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/template/create", name="template_create")
     */
    public function createTemplate(ValidatorInterface $validator, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
    {


        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $templateid = $template->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Template",$userid,$tablenameid,$templateid);

            return $this->redirectToRoute('template_show');
        }
        return $this->render('template/add.html.twig', ['form' => $form->createView(),'template' => $template]);

    }


    /**
     * @Route("/template/edit/{id}", name="template_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, TemplateRepository $templateRepository, Request $request, ActionLog $actionLog,TableListRepository $tablelistRepository): Response
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

            $user = $this->getUser();
            $userid = $user->getId();
            $templateid = $template->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Template'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Template",$userid,$tablenameid,$templateid);


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
    public function select(TemplateRepository $templateRepository, string $name, Request $request, PaginatorInterface $paginator): Response
    {
        //$template = $this->getDoctrine()
        //    ->getRepository(Template::class)
        //    ->findBy(array('decommissioned' => '0'),array('id' => 'ASC'));




            $q = $request->query->get('q');
            $queryBuilder = $templateRepository->getWithSearchQueryBuilder($q);
            $pagination = $paginator->paginate(
                $queryBuilder, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
            );
$session = new Session();
$session->start();

// set and get session attributes
$session->set('applicantid', $name);
        //return new Response('Check out this great template: '.$template->getJobId()
        // or render a template
        // in the template, print things with {{ template.name }}
return $this->render('template/select.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/template/use/{id}", name="template_use", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function use(int $id, TemplateRepository $templateRepository, ApplicantRepository $applicantRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $em = $this->getDoctrine()->getManager();

        $template_query = "SELECT template.id, template.name, user.name AS user, feedback_type.name AS feedback FROM template LEFT JOIN template_header ON template.header_id = template_header.id LEFT JOIN feedback_type ON template_header.feedbacktype_id = feedback_type.id LEFT JOIN user ON template_header.user_id = user.id WHERE template.id = '$id' and template.decommissioned = 0 and user.decommissioned = 0 and feedback_type.decommissioned = 0";
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

        $q = $request->query->get('q');
        $queryBuilder = $templateRepository->getWithSearchQueryBuilderTemplateUse($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );


        $response_query = "SELECT id, template_id, applicant_id, comment, job_id FROM feedback_response WHERE template_id = '$id' and applicant_id = '$applicant' and job_id = '$jobid'";
        $responsequery = $em->getConnection()->prepare($response_query);
        $responsequery->execute();

        $result_response = $responsequery->fetchAll();

        return $this->render('template/use.html.twig', ['pagination' => $pagination, 'template' => $result_template, 'applicantjob' => $result_applicantjob, 'response' => $result_response]);

    }

    /**
     * @Route("/template/display/{id}", name="template_display", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function display(int $id, TemplateRepository $templateRepository, ApplicantRepository $applicantRepository, JobRepository $jobRepository, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();


        $session = new Session();
$session->start();
$jobid = $session->get('jobid');
$applicantid = $session->get('applicantid');
$templateid = $session->get('template');

        $template_query = "SELECT template.id, template.name, user.name AS user, feedback_type.name AS feedback FROM template LEFT JOIN template_header ON template.header_id = template_header.id LEFT JOIN feedback_type ON template_header.feedbacktype_id = feedback_type.id LEFT JOIN user ON template_header.user_id = user.id WHERE template.id = '$templateid' and template.decommissioned = 0 and user.decommissioned = 0 and feedback_type.decommissioned = 0";
        $templatequery = $em->getConnection()->prepare($template_query);
        $templatequery->execute();

        $result_template = $templatequery->fetchAll();



        $applicant = $this->getDoctrine()
            ->getRepository(Applicant::class)
            ->findBy(array('name' => $applicantid),array('name' => 'ASC'),1 ,0)[0];

        $applicant = $applicant->getId();

        $job = $this->getDoctrine()
        ->getRepository(Job::class)
        ->findBy(array('id' => $jobid),array('name' => 'ASC'),1 ,0)[0];

        $job = $job->getName();


        $applicantjob_query = "SELECT applicant.name AS applicantname, job.name AS jobname FROM applicant LEFT JOIN job_applicant ON job_applicant.applicant_id = applicant.id LEFT JOIN job ON job_applicant.job_id = job.id WHERE job.id = '$jobid' AND applicant.name = '$applicantid' and applicant.decommissioned = 0 and job.decommissioned = 0";
        $applicantjobquery = $em->getConnection()->prepare($applicantjob_query);
        $applicantjobquery->execute();

        $result_applicantjob = $applicantjobquery->fetchAll();

        $statement_query = "SELECT statement.id, statement.statement, statement.statementtext, feedback_response_statement.id, template_statement.id FROM statement LEFT JOIN feedback_response_statement ON feedback_response_statement.statement_id = statement.id LEFT JOIN template_statement ON template_statement.statement_id = statement.id WHERE feedback_response_statement.id > 0 OR template_statement.id > 0 and template_statement.template_id = '$templateid'";
        $statementquery = $em->getConnection()->prepare($statement_query);
        $statementquery->execute();

        $result_statement = $statementquery->fetchAll();


        $response_query = "SELECT id, template_id, applicant_id, comment, job_id FROM feedback_response WHERE template_id = '$templateid' and applicant_id = '$applicant' and job_id = '$jobid'";
        $responsequery = $em->getConnection()->prepare($response_query);
        $responsequery->execute();

        $result_response = $responsequery->fetchAll();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $dompdf->loadHtml($this->render('template/display.html.twig', ['template' => $result_template, 'statement' => $result_statement, 'applicantjob' => $result_applicantjob, 'response' => $result_response, 'title' => "Feedback " . $id])->getContent());
       
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        $projectDir = $this->getParameter('kernel.project_dir');
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $projectDir . '\public\uploads\feedback/Feedback for ' . $job . ' and Applicant ' . $applicantid . '.pdf';
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
        
        // Send some text response
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT job.id, job_category.name AS category, job.name, job.description, job.duedate, SUM(CASE WHEN job_applicant.applicantresponded <> 1 and applicant.decommissioned = 0 THEN 1 ELSE 0 END) AS applicantcount 
            FROM job_category LEFT JOIN job ON job.jobcategory_id = job_category.id LEFT JOIN job_applicant ON job_applicant.job_id = job.id LEFT JOIN applicant ON job_applicant.applicant_id = applicant.id
            WHERE job.decommissioned = 0
            GROUP BY job.name ORDER BY job.id ASC';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result = $statement->fetchAll();
        //return $this->render('homepage/index.html.twig', ['job' => $result]);
        return $this->redirectToRoute('feedbackresponse_show');

    }
}