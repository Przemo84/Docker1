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
     * @Route("/art/{id}", name="list_articles")
     * @Method("GET")
     * @param null $id
     */
    public function listAction($id = null)
    {

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('jms_serializer');
        $paginator = $this->get('knp_paginator');


        $listOfArticles = $articleRepository->getQuery($id);

        $results = $paginator->paginate(
            $listOfArticles, 1, 10);

        return new Response($serializer->serialize($results, 'json'), 200, ['content-type()' => 'application/json']);
    }

    /**
     * @Route("/art/{id}", name="delete_article")
     * @Method("DELETE")
     * @param $id
     */
    public function deleteAction($id = null)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return new Response(null, 204);
    }

    /**
     * @Route("/art/{id}" , name="update_article")
     * @Method("PUT")
     * @param Request $request
     * @param $id
     */
    public function updateAction(Request $request, $id)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        dump($request->getUri()); die;

        $requestedBody = json_decode($request->getContent(), true);

        $articleToUpdate = $articleRepository->find($id);

        $form = $this->createForm(ArticleForm::class,$articleToUpdate);
        $form->submit($requestedBody);

        $articleRepository->update($articleToUpdate);

        return new Response(null, 200);
    }

    /**
     * @Route("/art", name="create_article")
     * @Method("POST")
     * @param Request $request
     */
    public function createAction(Request $request)
    {

        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $body = json_decode($request->getContent(),true);

        $newArticle = new Article();

        $form = $this->createForm(ArticleForm::class,$newArticle);
        $form->submit($body);

        $articleRepository->update($newArticle);

        return new Response(null, 201, ['content-type'=>'application/json']);
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