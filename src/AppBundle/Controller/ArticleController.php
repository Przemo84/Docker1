<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ArticleController extends Controller
{

    /**
     * @Route("/api/art/", name="api_list_articles")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('jms_serializer');
        $paginator = $this->get('knp_paginator');


        $listOfArticles = $articleRepository->listAll();

        $results = $paginator->paginate(
            $listOfArticles,
            $request->query->get('page', 1),
            $request->query->get('limit') ?? 10);

        $results = $serializer->serialize($results, 'json');

        return new Response($serializer->serialize($results, 'json'), 200, ['content-type' => 'application/json']);
    }

    /**
     * @Route("/api/art/{id}", name="api_show_article")
     * @Method("GET")
     */
    public function showAction($id)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('serializer');

        $oneArticle = $articleRepository->showOne($id);

        return new Response($serializer->serialize($oneArticle, 'json'), 200, ['content-type' => 'application/json']);
    }


    /**
     * @Route("/api/art/{id}", name="api_delete_article")
     * @Method("DELETE")
     */
    public function deleteAction($id = null)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return new Response(null, 204);
    }


    /**
     * @Route("/api/art/{id}" , name="api_update_article")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $requestedBody = json_decode($request->getContent(), true);

        $articleToUpdate = $articleRepository->find($id);

        $form = $this->createForm(ArticleForm::class, $articleToUpdate);
        $form->submit($requestedBody);

        $articleRepository->update($articleToUpdate);

        return new Response(null, 200);
    }

//     Body JSON:title, content, imageBase64_string

    /**
     * @Route("/api/create" , name="api_create_article")
     * @Method("POST")
     *
     */
    public function createAction(Request $request)
    {
        $articleRepository = $this->get('app.repo.articles');
        $imageFactory = $this->get('app.factory.images');

        $body = json_decode($request->getContent(), true);

        $newArticle = new Article();

        $form = $this->createForm(ArticleForm::class, $newArticle);
        $form->submit($body);

        try {
            $imageName = $imageFactory->upload($body['imageContent']);

            $newArticle->setImage($imageName);
            $articleRepository->update($newArticle);

        } catch (\InvalidArgumentException $e) {

            return new Response($e->getMessage(),
                422, ['content-type' => 'application/json']);
        };

        return new Response(null, 201, ['content-type' => 'application/json']);
    }

}