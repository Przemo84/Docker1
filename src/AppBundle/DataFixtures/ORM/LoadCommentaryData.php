<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Comments;
use Faker;

class LoadCommentaryData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i <= 50; $i++) {
            $userFaker = $faker->firstNameFemale;
            $commentFaker = $faker->realText($maxNbChars = 160);
            $idArticleFaker = $faker->numberBetween($min =1, $max=20);

            $comment = new Comments();
            $comment->setUsername($userFaker);
            $comment->setComment($commentFaker);
            $comment->setIdArticle($idArticleFaker);

            $manager->persist($comment);
            $manager->flush();
        }

    }

}