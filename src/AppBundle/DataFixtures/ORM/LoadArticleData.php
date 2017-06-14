<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use Faker;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

            $titleFaker = $faker->sentence(2);
            $contentFaker = $faker->realText($maxNbChars = 300);

            $article = new Article();
            $article->setTitle($titleFaker);
            $article->setContent($contentFaker);

            $manager->persist($article);
            $manager->flush();

            $this->setReference('article', $article);
    }

        public function getOrder()
    {
        return 1;
    }




}