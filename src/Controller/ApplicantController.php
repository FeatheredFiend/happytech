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
use App\Repository\TableListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\FileUploader;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class ApplicantController extends AbstractController
{

    /**
     * @Route("/applicant", name="applicant_show")
     */
    public function showAll(ApplicantRepository $applicantRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $applicantRepository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        //return new Response('Check out this great applicant: '.$applicant->getName()
        // or render a template
        // in the template, print things with {{ applicant.name }}
        return $this->render('applicant/showAll.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/applicant/create", name="applicant_create")
     */
    public function createApplicant(ValidatorInterface $validator, Request $request, FileUploader $fileUploader, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $applicant = new Applicant();

        $form = $this->createForm(ApplicantType::class, $applicant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $cvFile = $form->get('cv')->getData();
        if ($cvFile) {
            $cvFileName = $fileUploader->uploadCV($cvFile);
            $applicant->setCv($cvFileName);
        }
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($applicant);
            $em->flush();

	    $user = $this->getUser();
	    $userid = $user->getId();
	    $applicantid = $applicant->getId();
        $tablename = $tablelistRepository->findBy(array('name' => 'Applicant'),array('name' => 'ASC'),1 ,0)[0];
        $tablenameid = $tablename->getId();
	    $actionLog->addAction("Added Applicant",$userid,$tablenameid,$applicantid);

            return $this->redirectToRoute('applicant_show');
        }
        return $this->render('applicant/add.html.twig', ['form' => $form->createView(),'applicant' => $applicant]);

    }

    /**
     * @Route("/applicant/edit/{id}", name="applicant_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, ApplicantRepository $applicantRepository, Request $request, FileUploader $fileUploader, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $applicant = $applicantRepository
            ->find($id);

        $applicant->getName();
        $applicant->getEmail();


        $form = $this->createForm(ApplicantType::class, $applicant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $cvFile = $form->get('cv')->getData();
        if ($cvFile) {
            $cvFileName = $fileUploader->uploadCV($cvFile);
            $applicant->setCv($cvFileName);
        }

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($applicant);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $applicantid = $applicant->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Applicant'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Applicant",$userid,$tablenameid,$applicantid);

            return $this->redirectToRoute('applicant_show');
        }

        //return new Response('Check out this great applicant: '.$applicant->getName()
        // or render a template
        // in the template, print things with {{ applicant.name }}
        return $this->render('applicant/edit.html.twig', ['applicant' => $applicant,'form' => $form->createView()]);

}


}