<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['category'];
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
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

        foreach ($categories as $cat) {
            $category = new Category();

            $category->setName($cat);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
