<?php

namespace App\Controller;

use App\Entity\Statement;
use App\Form\StatementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\StatementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class StatementController extends AbstractController
{

    /**
     * @Route("/statement/create", name="statement_create")
     */
    public function createStatement(ValidatorInterface $validator, Request $request): Response
    {
        $statement = new Statement();

        $form = $this->createForm(StatementType::class, $statement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($statement);
            $em->flush();

            return $this->redirectToRoute('statement_show');
        }
        return $this->render('statement/add.html.twig', ['form' => $form->createView(),'statement' => $statement]);

    }

    /**
     * @Route("/statement", name="statement_show")
     */
    public function showAll(StatementRepository $statementRepository): Response
    {
        $statement = $statementRepository
            ->findAll();

        //return new Response('Check out this great statement: '.$statement->getName()
        // or render a template
        // in the template, print things with {{ statement.name }}
        return $this->render('statement/showAll.html.twig', ['statement' => $statement]);
    }

    /**
     * @Route("/statement/edit/{id}", name="statement_edit", requirements = {"id": "\d+"}, defaults={"id" = 1})
     */
    public function edit(int $id, StatementRepository $statementRepository, Request $request): Response
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
    public function open(int $id) :Response
    {
$session = new Session();
$session->start();

// set and get session attributes
$template = $session->get('template');

        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = "INSERT INTO feedbackresponse_statement(feedbackresponse_id, statement_id) VALUES ('$feedbackresponse','$id')";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
return new RedirectResponse('/template/use/'.$template);
}


}