<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\ArticleForm;
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

//        $results->

        return $this->render('articles/list.html.twig', [
            'results' => $results
        ]);
    }


    /**
     * @Route ("/a/{id}", name="show_article")
     * @Method("GET")
     * @param $id
     */
    public function showAction($id)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');
        $serializer = $this->get('serializer');

        $article = $articleRepository->showOne($id);

        return $this->render('articles/show.html.twig', [
        'oneArticle' => $article
    ]);
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

        return $this->render('articles/create.html.twig',[
            'form' => $form->createView()]);
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

        if ($form->isSubmitted() && $form->isValid()){

            $articleToUpdate = $form->getData();
            $articleRepository->update($articleToUpdate);

            return $this->redirectToRoute('list_all_articles');
        }

        return $this->render('articles/update.html.twig',[
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


    /**
     * @Route("/a/?search=/{param) , name="seaching"
     * @param $word
     */
    public function searchAction($word)
    {
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->search($word);

    }




}