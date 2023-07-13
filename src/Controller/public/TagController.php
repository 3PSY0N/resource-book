<?php

namespace App\Controller\public;

use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    public const MAX_PER_PAGE = 10;

    #[Route('/tags', name: 'public_tags_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('public/tag/index.html.twig', [
            'tags' => $tagRepository->findAllTags()
        ]);
    }

    #[Route('/tag/{slug}', name: 'public_tag_article_index', methods: ['GET'])]
    public function list($slug, Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $articleRepository->getArticlesByTag($slug, 'DESC'),
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('public/tag/article_list.html.twig', [
            'itemCount' => $pagination->getTotalItemCount(),
            'articles' => $pagination,
            'tag' => $slug
        ]);
    }
}
