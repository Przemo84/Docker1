<?php

namespace AppBundle\Controller;


use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\SerializerBundle\JMSSerializerBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NowyController extends Controller
{

    /**
     * @Route("/three", name="bla_bla")
     * @Method("GET")
     */
    public function listAction()
    {
        $articleRepository = $this->get('api_rest');

        $listGenerator = $articleRepository->showArticles();

        return new Response($listGenerator);
    }

}
