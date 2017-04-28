<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ArticlesController extends Controller
{
    /**
     * TODO pagination
     *
     * @Route("/articles", name="articles_list")
     * @Method("GET")
     */
    public function listAllAction()
    {

        $listOfArticles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();
        $zm1 = $this->get('');

        if (!$listOfArticles) {
            throw new \Exception("Brak rekordow w bazie");
        }

        //nie rzucamy wyjątku na najwyższym poziomie abstrakcji, 404 status code. Użytkownik nie ma prawa zobaczyć listy wyjątków z Symfony.
        // Nie rzucamy ogólnym wyjątkiem Exception!

        $serializer = $this->get("jms_serializer");


        $response2 = [];

        /** @var Article $item */
        foreach ($listOfArticles as $item) {
            $response2[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'content' => $item->getContent()
            ];
        }

        return new JsonResponse ($serializer->serialize($listOfArticles, 'json'));
    }

    /**
     * TODO delete all if id is null
     * @Route("/articles/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id = null)
    {
        $deletingRecord = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if (!$deletingRecord) {
            throw new \Exception(sprintf('Nie można usunąć tego rekordu. Rekord o id = "%s" nie istnieje', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($deletingRecord);
        $em->flush();

        return new Response();
    }

    /**
     * TODO doczytać create w RESTful
     * TODO 201 created status code i header location(opcjonalnie)
     * @Route("/articles"), name="new_article_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $jsonRequest = $request->getContent();
        $jsonArray = json_decode($jsonRequest, true);

        $newArticle = new Article();
        $newArticle->setTitle($jsonArray['title']);
        $newArticle->setContent($jsonArray['content']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newArticle);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/articles/{id} ", name="list_one_article")
     * @Method("GET")
     */
    public function showOneAction($id)
    {
        $articleFromDatabase = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if (!$articleFromDatabase) {
            throw new \Exception(sprintf('Rekord o id="%s" nie istnieje', $id));
        }

        $serializer = $this->get('jms_serializer');
        $jsonArticle = $serializer->serialize($articleFromDatabase, 'json');

        return new Response($jsonArticle);
    }

    /**
     * TODO 204 no content status code
     * @Route("/articles/{id} ", name="article_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $recordToUpdate = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if (!$recordToUpdate) {
            throw new \Exception(sprintf('Nie można uaktualnić tego rekordu. Rekord o id="%s" nie istnieje', $id));
        }

        $jsonArray = json_decode($request->getContent(), true);

        $recordToUpdate->setTitle($jsonArray['title']);
        $recordToUpdate->setContent($jsonArray['content']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($recordToUpdate);
        $em->flush();

        return new Response();
    }


    /**
     * @Route("/one", name="new_article_creating")
     * @Method("POST")
     */
    public function formCreateAction(Request $request)
    {
        $jsonArray = json_decode($request->getContent(), true);

        $articleObj = new Article();

        $form = $this->createForm(ArticleForm::class, $articleObj);
        $form->submit($jsonArray);


        $em = $this->getDoctrine()->getManager();
        $em->persist($articleObj);
        $em->flush();

        return new Response();


    }

    /**
     * @Route("/articles", name="delete_all_articles")
     * @Method("DELETE")
     */
    public function deleteAllAction()
    {
        $listToDelete = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        if (!$listToDelete) {
            throw new \Exception('Brak rekordów w bazie');
        }

        $em = $this->getDoctrine()->getManager();

        /** @var Article $item */
        foreach ($listToDelete as $item) {
            $em->remove($item);
            $em->flush();
        }

        return new Response();
    }


    /**
     * TODO pagination
     *
     * @Route("/two", name="articles_list")
     * @Method("GET")
     */
    public function listPaginateAction(Request $request)
    {

        $paginator = $this->get('knp_paginator');
        $page = $paginator->paginate(
            $query,
            $request->query->get('page',1),
            10
            );

        return new Response ();

    }
}