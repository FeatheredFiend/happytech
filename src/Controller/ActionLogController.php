<?php

namespace App\Controller;

use App\Entity\ActionLog;
use App\Repository\ActionLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class ActionLogController extends AbstractController
{
    /**
     * @Route("/action/log", name="action_log")
     */
    public function showAll(ActionLogRepository $actionlogRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $q = $request->query->get('q');
        $queryBuilder = $actionlogRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
 
        //return new Response('Check out this great actionlog: '.$actionlog->getName()
        // or render a template
        // in the template, print things with {{ actionlog.name }}
        return $this->render('action_log/showAll.html.twig', ['pagination' => $pagination]);
    }


}
