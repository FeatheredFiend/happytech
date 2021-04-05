<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobCategory;
use App\Entity\JobApplicant;
use App\Entity\Applicant;
use App\Form\JobType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JobRepository;
use App\Repository\JobCategoryRepository;
use App\Repository\JobApplicantRepository;
use App\Repository\ApplicantRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;


class HomepageController extends AbstractController
{

    /**
     * @Route("/homepage", name="homepage")
     */
    public function index(JobRepository $jobRepository, Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT job.id, job_category.name AS category, job.name, job.description, job.duedate, SUM(CASE WHEN job_applicant.applicantresponded <> 1 and applicant.decommissioned = 0 THEN 1 ELSE 0 END) AS applicantcount 
            FROM job_category LEFT JOIN job ON job.jobcategory_id = job_category.id LEFT JOIN job_applicant ON job_applicant.job_id = job.id LEFT JOIN applicant ON job_applicant.applicant_id = applicant.id
            WHERE job.decommissioned = 0 and job_applicant.emailed = 0
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
    public function email(MailerInterface $mailer, ApplicantRepository $applicantRepository, int $id)
    {


        $em = $this->getDoctrine()->getManager();

            $job = $this->getDoctrine()
            ->getRepository(Job::class)
            ->findBy(array('id' => $id),array('name' => 'ASC'),1 ,0)[0];
            $jobname = $job->getName();


            //retrieve all TaskUser object
$jobapplicant = $this->getDoctrine()->getManager();
$ja_repo = $jobapplicant->getRepository(JobApplicant::class);
$ja_array_collection = $ja_repo->findBy(array('job'=>$id));
foreach ($ja_array_collection as $ja) {

    $applicant = $this->getDoctrine()
    ->getRepository(Applicant::class)
    ->findBy(array('id' => $ja),array('name' => 'ASC'),1 ,0)[0];
    $applicantname = $applicant->getName();


                $projectDir = $this->getParameter('kernel.project_dir');
                // e.g /var/www/project/public/mypdf.pdf
                $pdfFilepath =  $projectDir . '\public\uploads\feedback/Feedback for ' . $jobname . ' and Applicant ' . $applicantname . '.pdf';


                $email = (new TemplatedEmail())
            ->from('symfonymailinghappytech@gmail.com')
            ->to('symfonymailinghappytech@gmail.com')
            ->subject('Feedback for Job! ' . $jobname . ' ' . $applicantname)
            ->attachFromPath($pdfFilepath);
                $mailer->send($email);


                $RAW_QUERY = "UPDATE job_applicant SET emailed = 1 WHERE job_id = '$id'";         
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();

                return $this->redirectToRoute('homepage');
            }




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