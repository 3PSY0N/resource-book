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
    private const MAX_PER_PAGE = 10;

    #[Route('/', name: 'public_article_index', methods: ['GET'])]
    public function index(
        Request            $request,
        ArticleRepository  $articleRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $paginatedArticles = $paginator->paginate(
            $articleRepository->getArticlesWithTags('DESC'),
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('public/article/index.html.twig', [
            'articles' => $paginatedArticles
        ]);
    }

    #[Route('/article/search', name: 'public_article_search', methods: ['GET'])]
    public function search(
        Request            $request,
        ArticleRepository  $articleRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $query = $request->get('q');

        if (empty($query)) {
            return $this->redirectToRoute('public_article_index');
        }

        $pagination = $paginator->paginate(
            $articleRepository->search($query, 'DESC'),
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('public/article/index.html.twig', [
            'articles' => $pagination,
            'query'    => $query
        ]);
    }

    #[Route('/article/{slug}', name: 'public_article_show', methods: ['GET'])]
    public function show($slug, ArticleRepository $articleRepository): Response
    {
        return $this->render('public/article/show.html.twig', [
            'article' => $articleRepository->findOneBySlug($slug)
        ]);
    }
}
