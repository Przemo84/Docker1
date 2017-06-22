<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Form\CommentForm;

use AppBundle\Form\ArticleForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WebController extends Controller
{

    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/aa/", name="article_index",  options={"expose"=true})
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.article');
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();

            $datatableQueryBuilder->buildQuery();

//            dump($datatableQueryBuilder->getQb()->getDQL()); die();

            return $responseService->getResponse();
        }

        return $this->render('articles/list2.html.twig', [
            'datatable' => $datatable,
        ]);
    }


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
     * @Route ("/a/read/{id}", name="show_article", options={"expose"=true})
     * @param $id
     */
    public function showAction2($id, Request $request)
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
            $newComment->setArticle($article);

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

            if ($newArticle->getImageFile()) {

            $file = $newArticle->getImageFile();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move( $this->getParameter('images_directory'), $fileName  );

            $newArticle->setImage($fileName);
        }
        $articleRepository->update($newArticle);

        return $this->redirectToRoute('article_index');
    }

return $this->render('articles/create.html.twig', ['form' => $form->createView(),]);
}


/**
 * @Route("/a/update/{id}" , name="update_article")
 * @param Request $request
 * @param $id
 */
public
function updateAction(Request $request, $id)
{

    /** @var $articleRepository $articleRepository */
    $articleRepository = $this->get('app.repo.articles');

    $articleToUpdate = $articleRepository->find($id);

    $form = $this->createForm(ArticleForm::class, $articleToUpdate);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $articleToUpdate = $form->getData();
        $articleRepository->update($articleToUpdate);

        return $this->redirectToRoute('article_index');
    }

    return $this->render('articles/update.html.twig', [
        'form' => $form->createView()]);
}

/**
 * @Route("/a/{id}", name="delete_article")
 * @param $id
 */
public
function deleteAction($id = null)
{
    /** @var $articleRepository $articleRepository */
    $articleRepository = $this->get('app.repo.articles');

    $articleRepository->delete($id);

    return $this->redirectToRoute('article_index');
}


//    /**
//     * @Route("/aa", name="store_file")
//     * @Method("POST")
//     */
//    public function storeImage()
//    {
//        $targetPath = substr(__DIR__, 0, -10) . "Images/";
//        $targetPath = $targetPath . $_FILES['uploadedFile']['name'];
////        dump($_FILES['uploadedFile']['type']);die;
//
//        if (substr($_FILES['uploadedFile']['name'], -3) == 'jpg') {
//
//            if ($_FILES['uploadedFile']['size'] < 200000) {
//
//                if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $targetPath)) {
//                    echo "File: " . $_FILES['uploadedFile']['name'] . " has been uploaded to Images/";
//                } else {
//                    echo "Error when uploading a file. Try again.";
//                }
//            }
//            echo 'Image size is to large. Maximum size of an image is 200kB.';
//        }
//        echo "Type of image file must be jpg, png, gif or bmp";
//
//
//        return $this->redirectToRoute('article_index');
//    }


}