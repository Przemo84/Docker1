<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use AppBundle\Repository\ArticleRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NowyController extends Controller
{

    /**
     * @Route("/article", name="listing_articles_page")
     * @Method("GET")
     */
    public function listAction()
    {

        $serializer = $this->get('jms_serializer');
        $paginator = $this->get('knp_paginator');

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $listGenerator = $articleRepository->getQuery();

        $resultPage = $paginator->paginate(
            $listGenerator, 1, 10,
            array('')
        );

        return new Response($serializer->serialize($resultPage, 'json'), 200, [
            'content-type' => 'application/json'
        ]);
    }

    /**
     * @Route("/article/{id}", name="show_one_article")
     * @Method("GET")
     * @param $id
     */
    public function showAction($id)
    {
        $serializer = $this->get('jms_serializer');
        $articleRepository = $this->get('app.repo.articles');

        $resultRecord = $articleRepository->getOne($id);

        return new Response($serializer->serialize($resultRecord, 'json'), 200);
    }


    /**
     * @Route("/article/{id}", name="delete_article")
     * @Method("DELETE")
     */
    public function purgeAction($id = null)
    {
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return new Response(null);


    }

    /**
     * @Route("/article", name="create_new_article")
     * @Method("POST")
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $article = new Article();

        $form = $this->createForm(ArticleForm::class, $article);
        $form->submit($body);

        $articleRepository->create($article);

        return new Response(null, 201);
    }

    /**
     * @Route("/article/{id}", name="update_article")
     * @Method("PUT")
     * @param Request $request
     */
    public function updateAction($id, Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $articleRepository = $this->get('app.repo.articles');

        $article = $articleRepository->find($id);

        if(null === $article)
        {
            return new Response(null, 404);
        }

        $form = $this->createForm(ArticleForm::class, $article);
        $form->submit($body);

        $articleRepository->update($article);

        return new Response(null, 201);
    }

    /**
     * @Route("/ar", name="listing_articles_via_query")
     * @Method("GET")
     */
    public function listTestAction()
    {
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('jms_serializer');

        $resultedQuery = $articleRepository->myQuery();

        return new Response($serializer->serialize($resultedQuery,'json'));
    }

}
