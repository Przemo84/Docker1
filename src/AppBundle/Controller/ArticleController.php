<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use AppBundle\Form\ImageForm;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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

    /**
     * @Route("/api/art", name="api_create_article")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $body = json_decode($request->getContent(), true);

        $newArticle = new Article();

        $form = $this->createForm(ArticleForm::class, $newArticle);
        $form->submit($body);

        $articleRepository->update($newArticle);

        return new Response(null, 201, ['content-type' => 'application/json']);
    }


// Body Request jako plik binarny. Bez zapisywania w bazie

    /**
     * @Route("/api/upload" , name="api_upload_image")
     * @Method("POST")
     *
     */
    public function uploadImage(Request $request)
    {
        $body = $request->getContent();
        $body = base64_encode($body);

        $form = $this->createForm(ImageForm::class);
        $form->submit($body);

        $data = $form->getViewData();
        $data = base64_decode($data);

        $image = imagecreatefromstring($data);
        $imageName = time();

        if ($image != false) {
            header('Content-Type: image/png');
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/' . $imageName . '.png';
            imagepng($image, $filePath);
            imagedestroy($image);
        } else {
            echo 'An error occurred.';
        }
        return new Response();
    }

//     Body JSON:title, content, imageBase64_string

    /**
     * @Route("/api/upload2" , name="api_upload_image2")
     * @Method("POST")
     *
     */
    public function uploadImage2(Request $request)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $body = json_decode($request->getContent(), true);
        dump($body);

        $newArticle = new Article();

        $form = $this->createForm(ArticleForm::class, $newArticle);
        $form->submit($body);

        dump($newArticle );die;




//        $image = imagecreatefromstring($data);
//
//        if ($image != false) {
//            header('Content-Type: image/png');
//            $filePath = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/zdjecie.png'; /
//            imagepng($image, $filePath);
//            imagedestroy($image);
//        }
//        else {
//            echo 'An error occurred.';
//        }
        return new Response(null, 201, ['content-type' => 'application/json']);
    }

}