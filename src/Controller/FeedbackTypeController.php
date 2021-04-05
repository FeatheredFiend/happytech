<?php

namespace App\Controller;

use App\Entity\FeedbackType;
use App\Form\FeedbackTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\FeedbackTypeRepository;
use App\Repository\TableListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ActionLog;
use Knp\Component\Pager\PaginatorInterface;

class FeedbackTypeController extends AbstractController
{
    
    /**
     * @Route("/feedbacktype", name="feedbacktype_show")
     */
    public function showAll(FeedbackTypeRepository $feedbacktypeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        
        $q = $request->query->get('q');
        $queryBuilder = $feedbacktypeRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
 

        //return new Response('Check out this great feedbacktype: '.$feedbacktype->getName()
        // or render a template
        // in the template, print things with {{ feedbacktype.name }}
        return $this->render('feedbacktype/showAll.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/feedbacktype/create", name="feedbacktype_create")
     */
    public function createFeedbackType(ValidatorInterface $validator, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $feedbacktype = new FeedbackType();

        $form = $this->createForm(FeedbackTypeType::class, $feedbacktype);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedbacktype);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $feedbacktypeid = $feedbacktype->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Feedback Type'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Added Feedback Type",$userid,$tablenameid,$feedbacktypeid);

            return $this->redirectToRoute('feedbacktype_show');
        }
        return $this->render('feedbacktype/add.html.twig', ['form' => $form->createView(),'feedbacktype' => $feedbacktype]);

    }


    /**
     * @Route("/feedbacktype/edit/{id}", name="feedbacktype_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, FeedbackTypeRepository $feedbacktypeRepository, Request $request, ActionLog $actionLog, TableListRepository $tablelistRepository): Response
    {
        $feedbacktype = $feedbacktypeRepository
            ->find($id);

        $feedbacktype->getName();



        $form = $this->createForm(FeedbackTypeType::class, $feedbacktype);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedbacktype);
            $em->flush();

            $user = $this->getUser();
            $userid = $user->getId();
            $feedbacktypeid = $feedbacktype->getId();
            $tablename = $tablelistRepository->findBy(array('name' => 'Feedback Type'),array('name' => 'ASC'),1 ,0)[0];
            $tablenameid = $tablename->getId();
            $actionLog->addAction("Edited Feedback Type",$userid,$tablenameid,$feedbacktypeid);

            return $this->redirectToRoute('feedbacktype_show');
        }

        //return new Response('Check out this great feedbacktype: '.$feedbacktype->getName()
        // or render a template
        // in the template, print things with {{ feedbacktype.name }}
        return $this->render('feedbacktype/edit.html.twig', ['feedbacktype' => $feedbacktype,'form' => $form->createView()]);

}


}