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
        $q = $request
            ->query
            ->get('q');
        $queryBuilder = $jobRepository->getWithSearchQueryBuilderHomepage($q);
        $pagination = $paginator->paginate($queryBuilder, /* query NOT result */
        $request
            ->query
            ->getInt('page', 1) /*page number*/
        , 5
        /*limit per page*/
        , array(
            'distinct' => false
        ));

        return $this->render('homepage/index.html.twig', ['pagination' => $pagination]);

    }

    /**
     * @Route("/job/open/{id}", name="job_open", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function open(int $id, JobRepository $jobRepository, Request $request, PaginatorInterface $paginator):
        Response
        {

            $q = $request
                ->query
                ->get('q');
            $queryBuilder = $jobRepository->getWithSearchQueryBuilderJobOpen($q, $id);
            $pagination = $paginator->paginate($queryBuilder, /* query NOT result */
            $request
                ->query
                ->getInt('page', 1) /*page number*/
            , 5
            /*limit per page*/);
            $session = new Session();
            $session->start();

            // set and get session attributes
            $session->set('jobid', $id);

            return $this->render('job/open.html.twig', ['pagination' => $pagination]);

        }

        /**
         * @Route("/email_applicants{id}", name="email_applicants", requirements = {"id": "\d+"}, defaults={"id" = 1})
         */
        public function email(MailerInterface $mailer, Request $request, JobRepository $jobRepository, ApplicantRepository $applicantRepository, int $id, PaginatorInterface $paginator)
        {

            $em = $this->getDoctrine()
                ->getManager();

            $job = $this->getDoctrine()
                ->getRepository(Job::class)
                ->findBy(array('id' => $id) , array('name' => 'ASC') , 1, 0)[0];
            $jobname = $job->getName();
            $jobid = $job->getId();

            $qb = $em->createQueryBuilder();
            $q = $qb->select(array('ja'))
                ->from('App:JobApplicant', 'ja')
                ->where('ja.job = ' . $jobid)->getQuery();


            $q->getResult();

            foreach ($q->getResult() as $key)
            {
                $applicantid = $key->getApplicant();
                echo '<br>' . $applicantid;

                $projectDir = $this->getParameter('kernel.project_dir');
                // e.g /var/www/project/public/mypdf.pdf
                $pdfFilepath = $projectDir . '\public\uploads\feedback/Feedback for ' . $jobname . ' and Applicant ' . $applicantid . '.pdf';

                $email = (new TemplatedEmail())->from('symfonymailinghappytech@gmail.com')
                //->to($applicantemail) This is the real email to, instead of the dummy with the same receiving address
                
                    ->to('symfonymailinghappytech@gmail.com') // Dummy Email address instead of using real email addresses to send the email
                
                    ->subject('Feedback for Job! ' . $jobname . ' ' . $applicantid)->attachFromPath($pdfFilepath);
                $mailer->send($email);

                $RAW_QUERY = "UPDATE job_applicant SET emailed = 1 WHERE job_id = '$id'";
                $statement = $em->getConnection()
                    ->prepare($RAW_QUERY);
                $statement->execute();

            }

            return $this->redirectToRoute('homepage');

        }
    }
    
