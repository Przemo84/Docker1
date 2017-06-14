<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Commentary;
use Faker;

class LoadCommentaryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();


            $userFaker = $faker->firstNameFemale;
            $commentFaker = $faker->realText($maxNbChars = 160);

            $comment = new Commentary();
            $comment->setUsername($userFaker);
            $comment->setComment($commentFaker);
            $comment->setArticle($this->getReference('article'));

            $manager->persist($comment);
            $manager->flush();

            $this->setReference('comment', $comment);


    }

    public function getOrder()
    {
        return 2;
    }


}