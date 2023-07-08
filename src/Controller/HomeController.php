<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use DateInterval;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public const MAX_PER_PAGE = 6;

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $cache = new FilesystemAdapter();
        $cachedContent = $cache->getItem('article_list');

        if (!$cachedContent->isHit()) {
            $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
            $articlesData = [];

            foreach ($articles as $article) {
                $categories = $categoryRepository->findByArticle($article);
                $articlesData[] = [
                    'article' => $article,
                    'categories' => $categories,
                ];
            }

            $cachedContent->set($articlesData);

            $cacheTtl = new DateInterval('P1D');
            $cachedContent->expiresAfter($cacheTtl);
            $cache->save($cachedContent);
        }
        $articles = $cachedContent->get();

        $paginatedArticles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('home/index.html.twig', [
            'articles' => $paginatedArticles
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $filter = $request->get('q');

        if (empty($filter)) {
            return $this->redirectToRoute('app_home');
        }

        $articlesSearch = $articleRepository->search($filter);

        $pagination = $paginator->paginate(
            $articlesSearch,
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('home/index.html.twig', [
            'articles' => $pagination
        ]);
    }

}
