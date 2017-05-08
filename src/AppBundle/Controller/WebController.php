<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class WebController extends Controller
{
    /**
     * @Route("/a/", name="list_all_articles")
     * @Method("GET")
     * @ Template("articles/list.html.twig")
     */
    public function listAllAction(Request $request)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        /** @ var $paginator $paginator */
        $paginator = $this->get('knp_paginator');


        $lista = $articleRepository->listAll();

        $results = $paginator->paginate($lista,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10));


        return $this->render('articles/list.html.twig', [
            'results' => $results
        ]);
    }

}