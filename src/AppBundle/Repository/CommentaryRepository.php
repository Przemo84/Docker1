<?php

namespace AppBundle\Repository;

/**
 * CommentaryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentaryRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param $id
     */
    public function listComments($id)
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->where('c.articleId =  :idArticle')
            ->setParameter('idArticle', $id);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $comment
     */
    public function createComment($comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }

}



