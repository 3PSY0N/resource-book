<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateInterval;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CachingService
{
    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getArticlesIndex(ArticleRepository $articleRepository, ?string $cacheTTL = 'PT1H'): mixed
    {
        return $this->getCachedData(
            'articles_index',
            function () use ($articleRepository) {
                return $articleRepository->findBy([], ['createdAt' => 'DESC']);
            },
            $cacheTTL
        );
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function getArticle(ArticleRepository $articleRepository, ?string $slug, ?string $cacheTTL = 'PT1H'): mixed
    {
        return $this->getCachedData(
            'article_' . $slug,
            function () use ($articleRepository, $slug) {
                return $articleRepository->findOneBySlug($slug);
            },
            $cacheTTL
        );
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    private function getCachedData(string $key, callable $getData, ?string $cacheTTL = 'PT1H'): mixed
    {
        $cachedContent = $this->cache->getItem($key);

        if (!$cachedContent->isHit()) {
            $cachedContent->set($getData());

            $cacheTtl = new DateInterval($cacheTTL);

            $cachedContent->expiresAfter($cacheTtl);

            $this->cache->save($cachedContent);
        }

        return $cachedContent->get();
    }
}