<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle('Article' . $i);
            $article->setUid(ByteString::fromRandom(10, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
