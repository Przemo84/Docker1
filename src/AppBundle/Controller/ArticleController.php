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


class ArticleController extends Controller
{

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


    public function showAction($id)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('serializer');

        $oneArticle = $articleRepository->showOne($id);

        return new Response($serializer->serialize($oneArticle, 'json'), 200, ['content-type' => 'application/json']);
    }


    public function deleteAction($id = null)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return new Response(null, 204);
    }


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