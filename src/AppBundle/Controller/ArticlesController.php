<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleForm;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends Controller
{
    /**
     * @Route("/article", name="articles_list")
     * @Method("GET")
     */
    public function getArticleAction()
    {
        $restResult = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        $serializer = $this->get("jms_serializer");

        $result2 = $serializer->serialize($restResult, 'json');
        $result = [];

        /** @var Article $item */
        foreach ($restResult as $item) {
            $result[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'content' => $item->getContent()
            ];
        }
        return new Response($result2);
    }

    /**
     * @Route("/article/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteArticleAction($id)
    {
        $deletingRecord = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($deletingRecord);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/article", name="new_article_create")
     * @Method("POST")
     */
    public function postArticleAction(Request $request)
    {

        $jsonRequest = $request;
        $jsonArray = json_decode($jsonRequest->getContent(), true);


        $newArticle = new Article();
        $newArticle->setTitle($jsonArray['title']);
        $newArticle->setContent($jsonArray['content']);


        $em = $this->getDoctrine()->getManager();
        $em->persist($newArticle);
        $em->flush();

        return new Response();
    }
    /**
     * @Route("/dupa", name="new_article_create")
     * @Method("POST")
     */
    public function putAction(Request $request)
    {
        $body = $request->getContent();
        $jsonArray = json_decode($request, true);

        $newArticle = new Article();
        $form = $this->createForm(new ArticleForm(), $newArticle );
        $form->submit($jsonArray);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newArticle);
        $em->flush();

        return new Response();
    }

}