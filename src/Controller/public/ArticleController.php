<?php

namespace App\Controller\public;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public const MAX_PER_PAGE = 10;

    #[Route('/', name: 'app_article_index')]
    public function index(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        $paginatedArticles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('public/article/index.html.twig', [
            'articles' => $paginatedArticles
        ]);
    }

    #[Route('/search', name: 'app_article_search')]
    public function search(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $filter = $request->get('q');

        if (empty($filter)) {
            return $this->redirectToRoute('app_article_index');
        }

        $articlesSearch = $articleRepository->search($filter);

        $pagination = $paginator->paginate(
            $articlesSearch,
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('public/article/index.html.twig', [
            'articles' => $pagination
        ]);
    }

    #[Route('/article/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show($slug, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBySlug($slug);

        return $this->render('public/article/show.html.twig', [
            'article' => $article
        ]);
    }
}
