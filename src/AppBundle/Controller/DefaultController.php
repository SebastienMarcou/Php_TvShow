<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/shows", name="shows")
     * @Template()
     */
    public function showsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');
        $result = $repo->findAll();
        $paginator = $this->get('knp_paginator');
        $paginate = $paginator->paginate($result, $request->query->getInt('page',1),6);

        return [
            'shows' => $paginate
        ];
    }

    /**
     * @Route("/show/{id}", name="show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');

        return [
            'show' => $repo->find($id)
        ];        
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $word = $request->get('search');
        $words = str_word_count($word, 1);


        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');

        $query = $repo->createQueryBuilder('q')
        ->where('q.name LIKE :keyword')
        ->setParameter(':keyword', '%' .$words[0] .'%');

        $query = $query->orderBy('q.name', 'ASC')->getQuery();
        $allShows = $query->getResult();

        return [
            'allShows' => $allShows,
            'search' => $words

        ];


    }

    /**
     * @Route("/calendar", name="calendar")
     * @Template()
     */
    public function calendarAction()
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Episode');

        $query = $repo->createQueryBuilder('q')
        ->where('DATE_DIFF(CURRENT_DATE(),q.date)<=0')
        ->orderBy('q.date','ASC')
        ->getQuery();

        $episodes = $query->getResult();



        return [
            'episodes' => $episodes
        ];
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        return [];
    }
}
