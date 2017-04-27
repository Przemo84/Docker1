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
            $listGenerator, 1, 10
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

        return new Response(null, 410);

    }

    /**
     * @Route("/article", name="create_new_article")
     * @Method("POST")
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $articleRepository = $this->get('app.repo.articles');
        $requestedData = json_decode($request->getContent(),'true');

        $article = new Article();

        $form = $this->createForm(ArticleForm::class, $article );
        $form->submit($requestedData);

        /**
         * TODO rozkminić createArticle przy pomocy formularza!! Ale przy zachowaniu dobrych reguł kodowania SOLID!!
         */




        return new Response(null, 201);
    }

    /**
     * @Route("/article/{id}", name="update_article")
     * @Method("PUT")
     * @param Request $request
     */
    public function updateAction($id, Request $request)
    {
        $articleRepository = $this->get('app.repo.articles');
        $articleRepository->update($id, $request);

        return new Response(null, 201);
    }

}
