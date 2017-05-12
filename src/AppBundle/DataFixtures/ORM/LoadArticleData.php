<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use Faker;

class LoadArticleData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
//        $faker = Faker\Factory::create();
//
//        for ($i = 1; $i <= 110; $i++) {
//            $titleFaker = $faker->sentence(2);
//            $contentFaker = $faker->realText($maxNbChars = 300);
//
//            $article1 = new Article();
//            $article1->setTitle($titleFaker);
//            $article1->setContent($contentFaker);
//
//            $manager->persist($article1);
//            $manager->flush();
//        }

    }

}