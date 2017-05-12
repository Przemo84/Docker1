<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Commentary;
use Faker;

class LoadCommentaryData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i <= 80; $i++) {
            $userFaker = $faker->firstNameFemale;
            $commentFaker = $faker->realText($maxNbChars = 160);
            $idArticleFaker = $faker->numberBetween($min =1, $max=25);

            $article = new Article();


            $comment = new Commentary();
            $comment->setUsername($userFaker);
            $comment->setComment($commentFaker);
            $comment->setArticle();

            $manager->persist($comment);
            $manager->flush();
        }

    }

}