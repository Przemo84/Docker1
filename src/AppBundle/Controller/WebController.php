<?php

namespace AppBundle\Controller;


use AppBundle\Form\CommentForm;

use AppBundle\Form\ArticleForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class WebController extends Controller
{
    /**
     * @Route("/a/", name="list_all_articles")
     * @ Template("articles/list.html.twig")
     */
    public function listAllAction(Request $request)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        /** @ var $paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $lista = $articleRepository->listAll($request->query->get('filter'));

        $results = $paginator->paginate($lista,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10));

        return $this->render('articles/list.html.twig', [
            'results' => $results]);
    }


    /**
     * @Route ("/a/read/{id}", name="show_article")
     * @param $id
     */
    public function showAction($id, Request $request)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        /** @var $commentaryRepository $commentaryRepository */
        $commentaryRepository = $this->get('app.repo.comments');

        $article = $articleRepository->showOne($id);
        $comments = $commentaryRepository->listComments($id);


        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $newComment = $commentForm->getData();
            $newComment->setIdArticle($id);

            $commentaryRepository->createComment($newComment);


            return $this->redirectToRoute('show_article', ['id' => $id]);
        }

        return $this->render('articles/show.html.twig', [
            'oneArticle' => $article,
            'comments' => $comments,
            'commentForm' => $commentForm->createView()]);
    }

    /**
     * @Route("/create", name="create_article")
     * @param Request $request
     */
    public function createAction(Request $request)
    {

        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $form = $this->createForm(ArticleForm::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $newArticle = $form->getData();
            $articleRepository->update($newArticle);

            return $this->redirectToRoute('list_all_articles');
        }

        return $this->render('articles/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/a/update/{id}" , name="update_article")
     * @param Request $request
     * @param $id
     */
    public function updateAction(Request $request, $id)
    {

        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleToUpdate = $articleRepository->find($id);

        $form = $this->createForm(ArticleForm::class, $articleToUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $articleToUpdate = $form->getData();
            $articleRepository->update($articleToUpdate);

            return $this->redirectToRoute('list_all_articles');
        }

        return $this->render('articles/update.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @Route("/a/{id}", name="delete_article")
     * @param $id
     */
    public function deleteAction($id = null)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return $this->redirectToRoute('list_all_articles');
    }


}