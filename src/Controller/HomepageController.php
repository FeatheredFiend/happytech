<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobCategory;
use App\Form\JobType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JobRepository;
use App\Repository\ApplicantRepository;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;

class HomepageController extends AbstractController
{

    /**
     * @Route("/homepage", name="homepage")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT job.id, job_category.name AS category, job.name, job.description, job.duedate, SUM(CASE WHEN job_applicant.applicantresponded <> 1 and applicant.decommissioned = 0 THEN 1 ELSE 0 END) AS applicantcount 
FROM job_category LEFT JOIN job ON job.jobcategory_id = job_category.id LEFT JOIN job_applicant ON job_applicant.job_id = job.id LEFT JOIN applicant ON job_applicant.applicant_id = applicant.id
WHERE job.decommissioned = 0
GROUP BY job.name ORDER BY job.id ASC';         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result = $statement->fetchAll();
        return $this->render('homepage/index.html.twig', ['job' => $result]);
    }

    /**
     * @Route("/job/open/{id}", name="job_open", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function open(int $id, JobRepository $jobRepository, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = "SELECT job.id, job.name AS job, applicant.name AS applicant FROM job LEFT JOIN job_applicant ON job_applicant.job_id = job.id LEFT JOIN applicant ON job_applicant.applicant_id = applicant.id WHERE job.id = '$id' and job_applicant.applicantresponded <> 1 and job.decommissioned = 0 and applicant.decommissioned = 0";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

$session = new Session();
$session->start();

// set and get session attributes
$session->set('jobid', $id);

        $result = $statement->fetchAll();
        return $this->render('job/open.html.twig', ['job' => $result]);

}

    /**
     * @Route("/email_applicants{id}", name="email_applicants", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function email(MailerInterface $mailer, ApplicantRepository $applicantRepository)
    {

        $applicant = $applicantRepository
            ->findAll();


        $email = (new TemplatedEmail())
            ->from('symfonymailinghappytech@gmail.com')
            ->to('symfonymailinghappytech@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('email/email.html.twig')
    	    ->context(['applicant' => $applicant]);
        $mailer->send($email);

        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT job.id, job_category.name AS category, job.name, job.description, job.duedate, SUM(CASE WHEN job_applicant.applicantresponded <> 1 and applicant.decommissioned = 0 THEN 1 ELSE 0 END) AS applicantcount 
FROM job_category LEFT JOIN job ON job.jobcategory_id = job_category.id LEFT JOIN job_applicant ON job_applicant.job_id = job.id LEFT JOIN applicant ON job_applicant.applicant_id = applicant.id
WHERE job.decommissioned = 0
GROUP BY job.name ORDER BY job.id ASC';         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result = $statement->fetchAll();
        return $this->render('homepage/index.html.twig', ['job' => $result]);
    }

}