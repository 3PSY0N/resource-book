<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleEntityTest extends KernelTestCase
{
    private ObjectManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testUniqueUid(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $article = new Article();
            $article->setTitle('My article');
            $article->setUid(Uuid::uuid7()->toString());

            $this->entityManager->persist($article);
            $this->addToAssertionCount(1);

            if (($i % 50) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }
}
