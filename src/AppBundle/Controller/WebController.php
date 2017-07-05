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
use Symfony\Component\Security\Core\User\UserInterface;


class WebController extends Controller
{
    public function logoutAction()
    {
        return new Response('<html><body>Zostałeś wylogowany!</body></html>');
    }


    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

// Wyświetla ładnie artykuły w DataTables
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

// Wyświetla artykułu po staremu
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


    public function showAction($id, Request $request)
    {
//  Wpuszcza jedynie jeżeli credencials się zgadzają i osoba ma rolę ROLE_USER.
//        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Dostep zabroniony');


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

                $file->move($this->getParameter('images_directory'), $fileName);

                $newArticle->setImage($fileName);
            }
            $articleRepository->update($newArticle);

            return $this->redirectToRoute('article_index');
        }

        return $this->render('articles/create.html.twig', ['form' => $form->createView(),]);
    }


    public function updateAction(Request $request, $id)
    {
//        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Dostep zabroniony');

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


    public function deleteAction($id = null)
    {
        /** @var $articleRepository $articleRepository */
        $articleRepository = $this->get('app.repo.articles');

        $articleRepository->delete($id);

        return $this->redirectToRoute('article_index');
    }

// Wysyłanie pliku. Wywołanie URL spowoduje że przeglądarka wyśle wskazany plik do pobrania użytkownikowi.
    public function fileAction()
    {
        $zm = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/ccac0ea4d31a06f37d83056b1260d938.jpeg';
        // send the file contents and force the browser to download it
        return $this->file($zm);
    }


    public function infoAction(UserInterface $user = null)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Dostep zabroniony');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        dump($user);die;
    }

}