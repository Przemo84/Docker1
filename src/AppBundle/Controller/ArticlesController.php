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
    public function listAllArticlesAction()
    {
        $listOfArticles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();
        if (!$listOfArticles ) {
            throw new \Exception("Brak rekordow w bazie");
        }

        $serializer = $this->get("jms_serializer");
        $response = new Response ($serializer->serialize($listOfArticles , 'json'));
        $response->headers->set('Content-Type', 'application-json');

        $response2= [];

        /** @var Article $item */
        foreach ($listOfArticles  as $item) {
            $response2[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'content' => $item->getContent()
            ];
        }
        return $response;
    }

    /**
     * @Route("/article/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteArticleAction($id)
    {
        $deletingRecord = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if(!$deletingRecord) {
            throw new \Exception(sprintf('Nie można usunąć tego rekordu. Rekord o id = "%s" nie istnieje',$id));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($deletingRecord);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/article", name="new_article_create")
     * @Method("POST")
     */
    public function createArticleAction(Request $request)
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
     * @Route("/article/{id} ", name="article_update")
     * @Method("PUT")
     */
    public function updateArticleAction(Request $request, $id)
    {
        $recordToUpdate = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if(!$recordToUpdate) {
            throw new \Exception(sprintf('Nie można uaktualnić tego rekordu. Rekord o id="%s" nie istnieje',$id));
        }

        $jsonArray = json_decode($request->getContent(),true);

        $recordToUpdate->setTitle($jsonArray['title']);
        $recordToUpdate->setContent($jsonArray['content']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($recordToUpdate);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/article/{id} ", name="list_one_article")
     * @Method("GET")
     */
    public function listOneArticleAction($id)
    {
        $articleFromDatabase = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

        if(!$articleFromDatabase) {
            throw new \Exception(sprintf('Rekord o id="%s" nie istnieje',$id));
        }

        $serializer = $this->get('jms_serializer');
        $jsonArticle = $serializer->serialize($articleFromDatabase, 'json');

        return new Response($jsonArticle);
    }

    /**
     * @Route("/one", name="new_article_creating")
     * @Method("POST")
     */
    public function formPostAction(Request $request)
    {
        $jsonArray = json_decode($request->getContent(), true);

        $ArticleObj = new Article();

        $form = $this -> createForm(ArticleForm::class, $ArticleObj);
        $form -> submit($jsonArray);

        $em = $this->getDoctrine()->getManager();
        $em -> persist($ArticleObj);
        $em -> flush();

        return new Response();
    }

    /**
     * @Route("/article", name="delete_all_articles")
     * @Method("DELETE")
     */
    public function deleteAllAction()
    {
        $listToDelete = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        if(!$listToDelete) {
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
}