<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['tags'];
    }

    public function load(ObjectManager $manager): void
    {
        $tags = [
            'Apache',
            'Bash',
            'Batch',
            'CSS',
            'Curl',
            'Fish',
            'HTML',
            'JavaScript',
            'JSON',
            'Markdown',
            'PHP',
            'PowerShell',
            'SQL',
            'TypeScript',
            'YAML',
            'Vue',
            'Alpine'
        ];

        foreach ($tags as $tag) {
            $tagEntity = new Tag();

            $tagEntity->setName($tag);

            $manager->persist($tagEntity);
        }

        $manager->flush();
    }
}
